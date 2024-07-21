<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Spatie\LaravelPdf\Facades\Pdf;

class PrintTicket extends Controller
{

    public function __invoke(Order $order)
    {
        $data = [
            'event_name' => 'MAHA JATRA AUCKLAND',
            'start_date' => 'SAT 15TH JUN 2024, 06:00 PM',
            'end_date' => 'SAT 15TH JUN 2024, 08:00 PM',
            'venue' => 'Victory Convention Centre, 98 Beaumont Street, Freemans Bay1010, Auckland, Auckland, New Zealand',
            'ticket_type' => 'STANDARD BLUE',
            'ticket_price' => '$59',
            'order_date' => 'SAT, 18 MAY 2024, 12:21 PM',
            'order_id' => '#77d5cf064b19b7cad9aa',
            'qr_code_url' => 'path_to_qr_code_image',
            'website' => 'https://www.aafnoticket.com',
            'tagline' => '"Aafno Ticket Nepal"',
        ];

        return Pdf::view('pdfs.print-ticket', $data)
            ->format('a4')
            ->name("$order->reference.pdf");
    }

}
