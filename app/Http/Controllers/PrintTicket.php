<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;

class PrintTicket extends Controller
{

    public function __invoke(Request $request, string $record)
    {
        $order = Order::whereId($record)->firstOrFail();

        return Pdf::view('pdfs.print-ticket', [
            'order' => $order,
            'eventDateTicketReference' => $request->query('event', 'all'),
            'eventtimezone' => 'Asia/Karachi',
            'eventdateFormat' => "ccc, d MMM Y hh:mm aaa"
        ])
            ->format('a4')
            ->name("$order->reference.pdf");
    }

}
