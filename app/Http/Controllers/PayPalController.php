<?php

namespace App\Http\Controllers;

use App\Traits\CreateOrder;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Throwable;

class PayPalController extends Controller
{
    use CreateOrder;

    /**
     * success transaction.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Throwable
     */
    public function successTransaction(Request $request): RedirectResponse
    {
        if (!Session::has('order_payload')) {
            abort(404);
        }

        $order = Session::get('order_payload');

        Session::forget('order_payload');

        $provider = new PayPalClient($order['config']);
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $this->createOrder(
                $order['tickets'],
                $order['user_id'],
                $order['payload'],
                $order['subtotal'],
                $response
            );

            return redirect()
                ->route('events', [
                    'type' => 'success',
                    'message' => 'Transaction complete.'
                ]);
        }

        return redirect()
            ->route('events', [
                'type' => 'error',
                'message' => $response['message'] ?? 'Something went wrong.'
            ]);
    }

    /**
     * cancel transaction.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     * @throws Throwable
     */
    public function cancelTransaction(Request $request): RedirectResponse
    {
        if (!Session::has('order_payload')) {
            abort(404);
        }

        $order = Session::get('order_payload');

        Session::forget('order_payload');

        $provider = new PayPalClient($order['config']);
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        return redirect()
            ->route('events', [
                'type' => 'error',
                'message' => $response['message'] ?? 'You have canceled the transaction.'
            ]);
    }
}
