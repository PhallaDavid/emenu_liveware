<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px dashed #ccc; padding-bottom: 10px; }
        .logo { font-size: 24px; font-weight: bold; }
        .info { font-size: 14px; margin-bottom: 20px; }
        table { w-full: 100%; width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { text-align: left; padding: 8px 0; border-bottom: 1px solid #eee; }
        .total { font-weight: bold; font-size: 18px; text-align: right; }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div class="logo">QR Menu</div>
        <div>Receipt</div>
    </div>

    <div class="info">
        <div><strong>Order ID:</strong> #{{ $order->id }}</div>
        <div><strong>Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div><strong>Table:</strong> {{ $order->table_number }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th style="text-align: right;">Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['qty'] }}</td>
                <td style="text-align: right;">${{ number_format($item['price'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total: ${{ number_format($order->total_price, 2) }}
    </div>

    <div class="footer">
        Thank you for dining with us!
    </div>

    <button class="no-print" onclick="window.print()" style="margin-top: 20px; padding: 10px 20px; cursor: pointer;">Print Receipt</button>
</body>
</html>
