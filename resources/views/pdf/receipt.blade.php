<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.6; }
        
        @php
            $type = strtolower($order->event->event_type ?? 'concert');
            $primaryColor = '#4361ee';
            if (str_contains($type, 'concert')) $primaryColor = '#f72585';
            elseif (str_contains($type, 'sport') || str_contains($type, 'football')) $primaryColor = '#4361ee';
            elseif (str_contains($type, 'theater') || str_contains($type, 'show')) $primaryColor = '#fb8500';
            elseif (str_contains($type, 'conference')) $primaryColor = '#00b4d8';
        @endphp

        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; color: #555; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr td:nth-child(2) { text-align: right; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; font-size: 20px; color: {{ $primaryColor }}; }
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .badge-paid { background: #e7f9ed; color: #28a745; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <span style="color: {{ $primaryColor }}; font-weight: 900;">VENUE</span>TICKETS
                            </td>
                            <td>
                                Receipt #: {{ $order->order_number }}<br>
                                Created: {{ $order->created_at->format('M d, Y') }}<br>
                                Paid: {{ $order->paid_at ? $order->paid_at->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <strong>Billed To:</strong><br>
                                {{ $order->user->name ?? 'N/A' }}<br>
                                {{ $order->user->email ?? 'N/A' }}
                            </td>
                            <td>
                                <strong>Event Details:</strong><br>
                                {{ $order->event->name ?? 'N/A' }}<br>
                                {{ $order->event->venue->name ?? 'Main Arena' }}<br>
                                {{ $order->event->event_date ? \Carbon\Carbon::parse($order->event->event_date)->format('M d, Y @ g:i A') : 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Payment Method</td>
                <td>Status</td>
            </tr>
            <tr class="details">
                <td>Stripe Credit Card</td>
                <td><span class="badge badge-paid">PAID</span></td>
            </tr>

            <tr class="heading">
                <td>Item</td>
                <td>Price</td>
            </tr>
            @foreach($order->tickets as $ticket)
            <tr class="item {{ $loop->last ? 'last' : '' }}">
                <td>{{ $ticket->pricingTier->name ?? 'Standard' }} Tier - Row {{ $ticket->row ?? 'GA' }}, Seat {{ $ticket->seat_number ?? 'GA' }}</td>
                <td>${{ number_format($ticket->price, 2) }}</td>
            </tr>
            @endforeach

            <tr><td></td><td></td></tr>
            
            <tr class="item">
                <td>Subtotal</td>
                <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr class="item">
                <td>Discount</td>
                <td>-${{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total">
                <td></td>
                <td>Total: ${{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
        
        <div class="footer">
            Thank you for choosing VenueTickets!<br>
            This is a computer-generated receipt and does not require a signature.
        </div>
    </div>
</body>
</html>