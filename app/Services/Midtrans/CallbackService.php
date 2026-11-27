<?php

namespace App\Services\Midtrans;

use App\Models\Checkout;
use App\Models\Order;
use Midtrans\Notification;

class CallbackService extends Midtrans
{
    protected $notification;
    protected $checkout;
    protected $serverKey;
    protected $signatureKey;

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = config('midtrans.server_key');
        $this->signatureKey = config('midtrans.signature_key') ?: $this->serverKey;
        $this->_handleNotification();
    }

   public function isSignatureKeyVerified()
{

    if (!$this->checkout) {
        return false;
    }

   return in_array((int) $this->notification->status_code, [200, 201]);
}
    public function isSuccess()
    {
        $statusCode = $this->notification->status_code;
        $transactionStatus = $this->notification->transaction_status;
        $fraudStatus = !empty($this->notification->fraud_status) ? ($this->notification->fraud_status == 'accept') : true;

        return ($statusCode == 200 && $fraudStatus && ($transactionStatus == 'capture' || $transactionStatus == 'settlement'));
    }

    public function isExpire()
    {
        return ($this->notification->transaction_status == 'expire');
    }

    public function isCancelled()
    {
        return ($this->notification->transaction_status == 'cancel');
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function getCheckout()
    {
        return $this->checkout;
    }

    public function getPaymentType()
    {
        return $this->notification->payment_type ?? null;
    }

    protected function _createLocalSignatureKey()
    {
        $orderId = $this->checkout->number;
        $statusCode = $this->notification->status_code;
        $grossAmount = $this->checkout->total_price;
        $serverKey = $this->serverKey;
        $input = $orderId . $statusCode . $grossAmount . $serverKey;
        $signature = openssl_digest($input, 'sha512');

        return $signature;
    }

    protected function _handleNotification()
    {
        $notification = new Notification();

        //$checkout = $notification->order_id;
        $checkout = Checkout::where('order_code', $notification->order_id)->first();

        $this->notification = $notification;
        $this->checkout = $checkout;
    }
}