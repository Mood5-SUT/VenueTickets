<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $ticket->ticket_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; margin: 0; padding: 0; }
        .ticket-container { width: 100%; border: 2px solid #eee; border-radius: 10px; overflow: hidden; margin: 20px; }
        
        /* Thematic Colors */
        @php
            $type = strtolower($ticket->event->event_type ?? 'concert');
            $primaryColor = '#333';
            if (str_contains($type, 'concert')) $primaryColor = '#f72585';
            elseif (str_contains($type, 'sport') || str_contains($type, 'football')) $primaryColor = '#4361ee';
            elseif (str_contains($type, 'theater') || str_contains($type, 'show')) $primaryColor = '#fb8500';
            elseif (str_contains($type, 'conference')) $primaryColor = '#00b4d8';
        @endphp
        
        .header { background: {{ $primaryColor }}; color: #fff; padding: 20px; text-align: center; }
        .content { padding: 30px; }
        .row { display: table; width: 100%; margin-bottom: 20px; }
        .col { display: table-cell; vertical-align: top; }
        .label { color: #999; font-size: 12px; text-transform: uppercase; margin-bottom: 5px; }
        .value { font-size: 18px; font-weight: bold; }
        .qr-section { text-align: center; padding: 30px; border-top: 1px dashed #eee; }
        .footer { background: #f9f9f9; padding: 15px; text-align: center; font-size: 10px; color: #999; }
        .text-primary { color: {{ $primaryColor }}; }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <h1 style="margin:0;">VENUE TICKETS</h1>
            <p style="margin:5px 0 0; opacity: 0.7;">Official Entry Pass</p>
        </div>
        <div class="content">
            <div class="row">
                <div class="col">
                    <div class="label">Event</div>
                    <div class="value">{{ $ticket->event->name }}</div>
                </div>
                <div class="col" style="text-align: right;">
                    <div class="label">Date</div>
                    <div class="value">{{ \Carbon\Carbon::parse($ticket->event->event_date)->format('M d, Y') }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="label">Venue</div>
                    <div class="value">{{ $ticket->event->venue->name }}</div>
                </div>
                <div class="col" style="text-align: right;">
                    <div class="label">Seat</div>
                    <div class="value">Row {{ $ticket->row }}, Seat {{ $ticket->seat_number }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="label">Tier</div>
                    <div class="value">{{ $ticket->pricingTier->name }}</div>
                </div>
                <div class="col" style="text-align: right;">
                    <div class="label">Ticket No.</div>
                    <div class="value">#{{ $ticket->ticket_number }}</div>
                </div>
            </div>
        </div>
        <div class="qr-section">
            <div class="label" style="margin-bottom: 15px;">Unique Entry QR Code</div>
            @if($qrCode === 'MOCK_QR_CODE_DATA')
                <div style="padding: 40px; background: #eee; border-radius: 10px; display: inline-block;">
                    [ QR CODE SCANNER ]
                    <br><small>{{ $ticket->qr_code }}</small>
                </div>
            @else
                <img src="data:image/svg+xml;base64, {!! base64_encode($qrCode) !!} " width="150" height="150">
            @endif
        </div>
        <div class="footer">
            Presented by {{ $ticket->event->organizer->organizerDetail->company_name ?? 'The Event Organizer' }}
            <br>This ticket is unique and should not be shared. Signed and encrypted for security.
        </div>
    </div>
</body>
</html>
