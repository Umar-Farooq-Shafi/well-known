<?php

namespace App\Traits;

use App\Models\Order;
use App\Models\OrderElement;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;

trait CreateOrder
{
    public function createOrder(
        array $tickets,
        int $userId,
        array $orderPayload,
        $subtotal,
        $detail = [],
    ): void
    {
        $user = User::find($userId);

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

            OrderElement::create([
                'order_id' => $order->id,
                'eventticket_id' => $ticket->id,
                'unitprice' => $unitprice,
                'quantity' => $quantity,
                'chosen_event_date' => $chosenDate
            ]);

            Payment::create([
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
        }
    }
}
