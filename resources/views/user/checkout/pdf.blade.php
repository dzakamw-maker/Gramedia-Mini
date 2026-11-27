<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan</title>
    <style>
        body{
            font-family: 'Helvetica', 'Arial',sans-serif;
            color: #333333;
            font-size: 13px;
            margin: 0;
            padding: 20px;     
        }
        .receipt-card{
            max-width: 420px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        td{
            padding: 4px 0;
            vertical-align: top;
        }

        .text-right{
            text-align: right;
        }

        .text-center{
            text-align: center;
        }

        .divider{
            border-top: 1px solid #e5e7eb;
            margin: 18px 0;
        }

        .item-name{
            padding-top: 10px;
            padding-bottom: 2px;
        }

        .item-price{
            padding-top: 0;
            padding-bottom: 10px;
            text-align: right;
        }

        .total-row td{
            padding-top: 8px;
            padding-bottom: 4px;
        }

        .badge-wrapper{
            text-align: center;
            margin-top: 22px;
        }

        .badge-lunas{
            display: inline-block;
            background-color: #f3f4f6;
            color: #6b7280;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 0.5px;
            padding: 5px 14px;
            border-radius: 4px;
            border: 1px solid #e5e7eb;
        }
    </style>
</head>

<body>        
    <div class="receipt-card">
        <table>
            <tr>
                <td>No. Pesanan</td>
                <td class="text-right">{{ $checkout['order_code'] }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td class="text-right">{{ $checkout['paid_date'] ? \Carbon\Carbon::parse($checkout['paid_date'])->format('d/m/Y H:i:s') : '-' }}</td>
            </tr>
        </table>

        <div class="divider"></div>

        <!-- Daftar item -->
         <table>
            @foreach ($checkout['checkoutBooks'] as $item)
            <tr>
                <td colspan="2" class="item-name">{{ $item['book']['title'] ?? '-' }}</td>
            </tr>

            <tr>
                <td colspan="2" class="item-price">{{ number_format($item['price'] ?? 0,0,',','.') }}</td>
            </tr>
            @endforeach

            <!-- Total -->
             <tr class="total-row">
                <td>TOTAL</td>
                <td class="text-right">{{ number_format($checkout['total_price'] ?? 0,0,',','.') }}</td>
             </tr>
         </table>
         
         <div class="divider"></div>

         <div class="badge-wrapper">
            <span class="badge-lunas">LUNAS</span>
         </div>
    </div>
</body>

</html>