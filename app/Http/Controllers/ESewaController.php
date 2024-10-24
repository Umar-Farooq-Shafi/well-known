<?php

namespace App\Http\Controllers;

use App\Traits\CreateOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ESewaController extends Controller
{
    use CreateOrder;

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function esewaSuccess(Request $request): RedirectResponse
    {
        if (!Session::has('order_payload')) {
            abort(404);
        }

        $order = Session::get('order_payload');
        Session::forget('order_payload');

        $decodedString = base64_decode($request->get('data'));
        $data = json_decode($decodedString, true);

        if (data_get($data, 'status', '') === "COMPLETE") {
            $orderIds = $this->createOrder(
                $order['tickets'],
                $order['user_id'],
                $order['payload'],
                $order['subtotal'],
                $data
            );

            if (count($orderIds)) {
                return redirect()
                    ->route('filament.admin.resources.orders.view', ['record' => $orderIds[0]]);
            }

            return redirect()
                ->route('events', [
                    'type' => 'success',
                    'message' => 'Transaction complete.'
                ]);
        }

        return redirect()
            ->route('events', [
                'type' => 'error',
                'message' => $data['message'] ?? 'Something went wrong.'
            ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function esewaError(Request $request): RedirectResponse
    {
        if (!Session::has('order_payload')) {
            abort(404);
        }

        Session::forget('order_payload');

        $decodedString = base64_decode($request->get('data'));
        $data = json_decode($decodedString, true);

        return redirect()
            ->route('events', [
                'type' => 'error',
                'message' => $data['message'] ?? 'You have canceled the transaction.'
            ]);
    }
}
