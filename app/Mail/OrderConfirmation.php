<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Spatie\LaravelPdf\Facades\Pdf;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Model|int|null|string $order)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your tickets bought from "Aafno Ticket Nepal"',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.order-confirmation',
            with: [
                'order' => $this->order,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        Pdf::view('pdfs.print-ticket', [
            'order' => $this->order,
            'eventDateTicketReference' => 'all',
            'eventtimezone' => 'Asia/Karachi',
            'eventdateFormat' => "ccc, d MMM Y hh:mm aaa"
        ])
            ->format('a4')
            ->disk('public')
            ->save("pdf/" . $this->order->reference . ".pdf");

        return [
            Attachment::fromPath(Storage::disk('public')->path("pdf/" . $this->order->reference . ".pdf"))
        ];
    }
}
