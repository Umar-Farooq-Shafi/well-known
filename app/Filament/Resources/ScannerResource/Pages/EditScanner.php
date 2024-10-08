<?php

namespace App\Filament\Resources\ScannerResource\Pages;

use App\Filament\Resources\ScannerResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditScanner extends EditRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = ScannerResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        $data['username'] = $user?->username;
        $data['email'] = $user?->email;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'name' => $data['name']
        ]);

        $user = $record->user;

        $user->update([
            'username' => $data['username'],
            'username_canonical' => strtolower($data['username']),
            'email' => $data['email'],
            'email_canonical' => strtolower($data['email']),
            'slug' => Str::slug($data['username'])
        ]);

        if ($data['password']) {
            $user->update([
                'password' => Hash::make($data['password'])
            ]);
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
