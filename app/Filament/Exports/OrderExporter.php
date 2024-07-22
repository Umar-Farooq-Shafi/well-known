<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('reference'),

            ExportColumn::make('status')
                ->formatStateUsing(function ($state): string {
                    return match ($state) {
                        1 => 'Paid',
                        0 => 'Awaiting payment',
                        -1 => 'Cancel',
                        default => $state
                    };
                }),

            ExportColumn::make('created_at')
                ->label('Order Date'),

            ExportColumn::make('paymentGateway.organizer.name'),

            ExportColumn::make('eventDateTickets.name')
                ->label('Event'),

            ExportColumn::make('user.fullName')
                ->label('Attendee / POS'),

            ExportColumn::make('user.email'),

            ExportColumn::make('orderElements.quantity'),

            ExportColumn::make('orderElements.unitprice'),

            ExportColumn::make('paymentGateway.name'),

            ExportColumn::make('user.street'),

            ExportColumn::make('user.street2'),

            ExportColumn::make('user.city'),

            ExportColumn::make('user.state'),

            ExportColumn::make('user.postalcode'),

            ExportColumn::make('user.country'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
