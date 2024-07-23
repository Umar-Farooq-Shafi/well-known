@php use Illuminate\Support\Carbon; @endphp
    <!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <style>

        html {
            margin-top: 2px !important;
            /*margin-left:5% !important;*/
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4em;
            font-weight: bold;
        }

        table, th, td {
            border: 1px solid #000000;
            border-collapse: collapse;
        }

        .ticket-wrapper {
            display: block;
            width: 71mm;
            margin-left: -7mm;

        }

        .label {
            color: #000000;
            display: block;
            text-transform: uppercase;
            text-align: left;
        }

        .value {
            display: block;
            color: #121212;
            text-transform: uppercase;
            overflow: hidden;
            font-size: 10px;
            text-align: left;
        }

        .valuetop {
            display: block;
            color: #ffffff;
            text-transform: uppercase;
            overflow: hidden;
            font-size: 10px;
            text-align: left;
        }

        .center {
            display: block;
            text-align: center;

        }

        .tickets-container .ticket-wrapper {
            page-break-inside: avoid;
        }

        .tickets-container .ticket-wrapper:not(:last-child) {
            page-break-after: always;
        }

        .tickets-container .ticket-wrapper:not(:first-child) {
            page-break-before: always;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            font-size: 8px;
        }

        #title {
            height: 20px;
        }

        * {
            font-family: DejaVu Sans, sans-serif !important;
        }
    </style>
</head>

<body>
<div class="tickets-container">
    @foreach ($order->orderelements as $orderElement)
        @if ($eventDateTicketReference == "all" || $eventDateTicketReference == $orderElement->eventDateTicket->reference)
            @if (auth()->user()->hasRole('ROLE_ADMINISTRATOR') || auth()->user()->hasRole('ROLE_ATTENDEE') ||
                (auth()->user()->hasRole('ROLE_ORGANIZER') && $orderElement->belongsToOrganizer(auth()->user()->organizer->slug)) ||
                (auth()->user()->hasRole('ROLE_POINTOFSALE') && $orderElement->belongsToOrganizer(auth()->user()->pointOfSale->organizer->slug)))

                @foreach ($orderElement->orderTickets as $ticket)
                    <div class="ticket-wrapper">
                        <table style="width:100%">
                            <tr style="background: #000000;">
                                <th style="width:100%"><span id="title"
                                                             class="valuetop">{{ $orderElement->eventDateTicket->eventDate->event->name }}</span>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                <span class="value">
                                    @if ($orderElement->chosen_event_date !== null)
                                        Your Selected
                                        Date: {{ Carbon::make($orderElement->chosen_event_date)->format('D j M Y') }} {{ strtoupper(Carbon::make($orderElement->eventDateTicket->eventDate->startdate)->format('g:i A')) }}
                                    @elseif ($orderElement->eventDateTicket->eventDate->enddate !== null)
                                        {{ Carbon::make($orderElement->eventDateTicket->eventDate->startdate)->format('D jS M Y , h:i A') }}
                                        <br>To
                                        <br> {{ Carbon::make($orderElement->eventDateTicket->eventDate->enddate)->format('D jS M Y , h:i A') }}
                                    @else
                                        {{ Carbon::make($orderElement->eventDateTicket->eventDate->startdate)->format('D jS M Y , h:i A') }}
                                    @endif
                                </span>
                                </td>
                            </tr>
                        </table>
                        <span class="center">
                        <i class="fas fa-map-marker-check"></i>
                        {{ __("Venue / Location", [], "messages", app()->getLocale()) }}:
                        @if ($orderElement->eventDateTicket->eventDate->venue)
                                {{ $orderElement->eventDateTicket->eventDate->venue->name }}
                                <br>
                                {{ $orderElement->eventDateTicket->eventDate->venue->stringifyAddress }}
                            @else
                                {{ __("Online", [], "messages", app()->getLocale()) }}
                            @endif
                    </span>
                        <br>
                        <table style="width:100%; border:0px !important;">
                            <tr>
                                <td style="width:60%; border:0px !important;">
                                <span class="center">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::generate($ticket->reference) !!}
                                </span>
                                    <span class="center">
                                    #{{ $ticket->reference }}
                                </span>
                                </td>
                                <td style="width:40%; border: 0 !important;">
                                <span class="value">
                                    {{ $orderElement->eventDateTicket->name }}
                                </span>
                                    <span class="value">
                                    {{ $orderElement->eventDateTicket->free ? __("Free", [], "messages", app()->getLocale()) : ((config('settings.currency_position') == 'left' ? $orderElement->eventDateTicket->currencyCode->symbol : '') . $orderElement->displayUnitPrice() . (config('settings.currency_position') == 'right' ? $orderElement->eventDateTicket->currencyCode->symbol : '')) }}
                                </span>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <table style="width:100%">
                            <tr>
                                <th style="width:50%">
                                    <span class="label">Order details </span>
                                    <span id="name" class="value">
                                    {{ $order->user->getCrossRoleName() }}
                                        @if ($order->user->hasRole('ROLE_POINTOFSALE') && $order->payment->firstname && $order->payment->lastname)
                                            ({{ $order->payment->firstname . " " . $order->payment->lastname }})
                                        @endif
                                </span>
                                </th>
                                <th style="width:50%">
                                    <span class="label">{{ $order->paymentGateway->name }}</span>
                                    <span id="name" class="value">
                                    @if (isset($order->payment->details["TIMESTAMP"]))
                                            {{ $order->payment->details["TIMESTAMP"] }}
                                        @elseif (isset($order->payment->details["created"]))
                                            {{ $order->payment->details["created"] }}
                                        @else
                                            {{ $order->payment->updated_at}}
                                        @endif
                                </span>
                                </th>
                            </tr>
                        </table>
                        <div class="end">
                        <span class="center">
                            {{ env('APP_URL') }}
                            <br>
                            {{ env('APP_NAME') }}
                        </span>
                        </div>
                    </div>
                @endforeach
            @endif
        @endif
    @endforeach
    <br><br><br>

</div>
</body>
</html>
