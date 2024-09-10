<?php

namespace App\Filament\Resources\PaymentGatewayResource\Pages;

use App\Filament\Resources\PaymentGatewayResource;
use App\Traits\PaymentGatewayTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditPaymentGateway extends EditRecord
{
    use PaymentGatewayTrait;

    protected static string $resource = PaymentGatewayResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->handlePaymentForm($data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function ($record) {
                    DB::transaction(function () use ($record) {
                        foreach ($record->deletedOrders as $order) {
                            DB::table('eventic_order')
                                ->where('id', $order->id)
                                ->update(['payment_id' => null]);

                            DB::table('eventic_payment')->where('order_id', $order->id)->delete();

                            foreach ($order->orderElements()->withTrashed()->get() as $orderElement) {
                                foreach ($orderElement->ticketReservations()->withTrashed()->get() as $ticketReservation) {
                                    DB::table('eventic_ticket_reservation')
                                        ->where('id', $ticketReservation->id)
                                        ->update(['orderelement_id' => null]);

                                    $ticketReservation->delete();
                                }

                                DB::table('eventic_order_element')
                                    ->where('id', $orderElement->id)
                                    ->update(['order_id' => null]);

                                DB::table('eventic_order_element')->where('id', $orderElement->id)->delete();
                            }
                        }

                        $record->deletedOrders()->forceDelete();

                        $record->eventDateTickets()->detach();
                    });
                }),
        ];
    }
}
