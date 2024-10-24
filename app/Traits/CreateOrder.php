<?php

namespace App\Traits;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Models\OrderElement;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait CreateOrder
{
    public function createOrder(
        array $tickets,
        int $userId,
        array $orderPayload,
        $subtotal,
        $detail = [],
    ): array
    {
        $user = User::find($userId);
        $orderIds = [];

        foreach ($tickets as $ticket) {
            $order = Order::create($orderPayload);

            $cartElements = $ticket->cartElements()
                ->where('user_id', $userId)
                ->get();

            $unitprice = 0;
            $quantity = 0;
            $chosenDate = null;

            foreach ($cartElements as $ce) {
                $unitprice += $ce->unitprice;
                $quantity += $ce->quantity;
                $chosenDate = $ce->chosen_event_date;
            }

            $ticket->cartElements()
                ->where('user_id', $userId)
                ->delete();

            OrderElement::create([
                'order_id' => $order->id,
                'eventticket_id' => $ticket->id,
                'unitprice' => $unitprice,
                'quantity' => $quantity,
                'chosen_event_date' => $chosenDate
            ]);

            $payment = Payment::create([
                'order_id' => $order->id,
                'country_id' => $user->country_id,
                'number' => Str::uuid(),
                'description' => 'Payment of tickets purchased on "Aafno Ticket Nepal"',
                'client_email' => $user->email,
                'client_id' => $userId,
                'total_amount' => $subtotal,
                'currency_code' => $orderPayload['currency_ccy'],
                'details' => $detail,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'state' => $user->state,
                'city' => $user->city,
                'postalcode' => $user->postalcode,
                'street' => $user->street,
                'street2' => $user->street2
            ]);

            $order->update(['payment_id' => $payment->id]);
            $orderIds[] = $order->id;

            Mail::to(auth()->user()->email)->send(new OrderConfirmation($order));

        }

        return $orderIds;
    }
}
