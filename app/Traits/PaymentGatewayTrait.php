<?php

namespace App\Traits;

use App\Models\PaymentGateway;
use Illuminate\Support\Str;

trait PaymentGatewayTrait
{
    /**
     * @param array $data
     * @return array
     */
    public function handlePaymentForm(array $data): array
    {
        if (array_key_exists('gateway_logo_name', $data) && $data['gateway_logo_name'] !== null) {
            $data['gateway_logo_name'] = last(explode('/', $data['gateway_logo_name']));
        } else if ($data['gateway_name'] === 'paypal_express_checkout') {
            $adminGateway = PaymentGateway::whereGatewayName('paypal_express_checkout')
                ->whereNotNull('gateway_logo_name')
                ->first();

            if ($adminGateway) {
                $data['gateway_logo_name'] = $adminGateway->gateway_logo_name;
            }
        } else if ($data['gateway_name'] === 'stripe_checkout') {
            $adminGateway = PaymentGateway::whereGatewayName('stripe_checkout')
                ->whereNotNull('gateway_logo_name')
                ->first();

            if ($adminGateway) {
                $data['gateway_logo_name'] = $adminGateway->gateway_logo_name;
            }
        } else {
            $adminGateway = PaymentGateway::query()
                ->whereNotNull('gateway_logo_name')
                ->first();

            if ($adminGateway) {
                $data['gateway_logo_name'] = $adminGateway->gateway_logo_name;
            }
        }

        if (auth()->user()->hasRole('ROLE_ORGANIZER')) {
            $data['organizer_id'] = auth()->user()->organizer_id;
        }

        $data['factory_name'] = $data['gateway_name'];
        $data['slug'] = Str::slug($data['name']);

        if (!array_key_exists('config', $data)) {
            $data['config'] = json_encode([]);
        }

        return $data;
    }
}
