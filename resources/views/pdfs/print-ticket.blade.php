<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .ticket {
            width: 100%;
            border: 1px solid #000;
            padding: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            font-size: 20px;
        }
        .details {
            margin-top: 20px;
        }
        .details p {
            margin: 5px 0;
        }
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">{{ $event_name }}</div>
    <div class="details">
        <p><strong>From:</strong> {{ $start_date }}</p>
        <p><strong>To:</strong> {{ $end_date }}</p>
        <p><strong>Venue:</strong> {{ $venue }}</p>
        <p><strong>Ticket Type:</strong> {{ $ticket_type }}</p>
        <p><strong>Price:</strong> {{ $ticket_price }}</p>
        <p><strong>Order Date:</strong> {{ $order_date }}</p>
        <p><strong>Order ID:</strong> {{ $order_id }}</p>
    </div>
    <div class="qr-code">
        <img src="{{ $qr_code_url }}" alt="QR Code">
    </div>
    <div class="footer">
        <p>{{ $website }}</p>
        <p>{{ $tagline }}</p>
    </div>
</div>
</body>
</html>
