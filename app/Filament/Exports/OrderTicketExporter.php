<?php

namespace App\Filament\Exports;

use App\Models\OrderTicket;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class OrderTicketExporter extends Exporter
{
    protected static ?string $model = OrderTicket::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('reference')
                ->label('#'),

            ExportColumn::make('orderElement.order.payment.firstname')
                ->label('Attendee First Name'),

            ExportColumn::make('orderElement.order.payment.lastname')
                ->label('Attendee Last Name'),

            ExportColumn::make('orderElement.order.payment.client_email')
                ->label('Attendee Email'),

            ExportColumn::make('orderElement.eventDateTicket.name')
                ->label('Ticket Type'),

            ExportColumn::make('created_at')
                ->label('Bought on')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order ticket export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}