<?php

namespace App\Http\Controllers;

use App\Models\Checkout;
use App\Models\CheckoutBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Services\Midtrans\CallbackService;
use Faker\Provider\Payment;
use Barryvdh\DomPDF\Facade\Pdf;


class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'selected_carts'=> ['required', 'array', 'min:1'],
            'selected_carts.*'=> ['exists:carts,id']
        ]);

        $selectedCarts=Cart::where('user_id', Auth::user() ->id)
        ->whereIn('id', $request->selected_carts)
        ->with(['book', 'subscriptionPackage'])->get();

        if(empty($selectedCarts)){
            return redirect()->back()->with('error','Tidak ada buku yang dipilih');
        }
        $prices = $selectedCarts->mapWithKeys(fn($row) => [
            $row->id => $row->subscription_package_id == null
                ? $row->book->price
                : $row->subscriptionPackage->price,
        ]);
        $totalPrice =$prices->sum();

        DB::beginTransaction();
        try{
            $checkout = Checkout::create([
                'user_id' => Auth::id(),
                'total_book_price' => $totalPrice,
                'total_price' => $totalPrice,
                'total_book' => $selectedCarts->count(),
                'status' => 'pending',
                'order_code' => 'ORD-' .time(),
            ]);

            foreach ($selectedCarts as $item) {
                CheckoutBook::create([
                'book_id' => $item->book_id,
                'subscription_package_id' => $item->subscription_package_id,
                'price' => $prices[$item->id],
                'checkout_id' => $checkout->id,
                ]);
            };

            Cart::whereIn('id', $request->selected_carts)->delete();
            DB::commit();
            return redirect()->route('checkout.show', $checkout->id);
        } catch(\Throwable $th){
            DB::rollBack();
            dd($th);
            return redirect()->back()->with('error','Terjadi kesalahan saat checkout. Silahkan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $checkout = Checkout::findOrFail($id);
        $snapToken = $checkout->snap_token;
         if (is_null($snapToken)) {
             // If snap token is still NULL, generate snap token and save it to database

             $midtrans = new CreateSnapTokenService($checkout);
             $snapToken = $midtrans->getSnapToken();

             $checkout->snap_token = $snapToken;
             $checkout->save();
         }

         return view('user.checkout.show', compact('checkout', 'snapToken'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checkout $checkout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Checkout $checkout)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checkout $checkout)
    {
        //
    }

    public function receive()
    {
        $callback = new CallbackService();

        if ($callback->isSignatureKeyVerified()) {
            $checkout = $callback->getCheckout();

            if ($callback->isSuccess()) {
                $checkout->update([
                    'status' => 'paid',
                    'payment_method' => $callback->getPaymentType(),
                    'paid_date' => now(),
                ]);
            } elseif ($callback->isExpire()) {
                $checkout->update([
                    'status' => 'Expired',
                ]);
            } elseif ($callback->isCancelled()) {
                $checkout->update([
                    'status' => 'Failed',
                ]);
            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notification successfully processed',
                ]);
        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key not verified',
                ], 403);
        }
    }

    public function exportPDF($id)
    {
        $checkout = Checkout::where('id' ,$id)
        ->with(['checkoutBooks', 'checkoutBooks.book'])
        ->first();

        $pdf = Pdf::loadView('user.checkout.pdf', compact('checkout'));

        return $pdf->download($checkout['order_code'] . '.pdf');
        
    }
}
