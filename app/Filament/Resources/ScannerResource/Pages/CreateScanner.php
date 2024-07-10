<?php

namespace App\Filament\Resources\ScannerResource\Pages;

use App\Filament\Resources\ScannerResource;
use App\Models\Scanner;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateScanner extends CreateRecord
{
    protected static string $resource = ScannerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'username' => $data['username'],
            'username_canonical' => strtolower($data['username']),
            'email' => $data['email'],
            'email_canonical' => strtolower($data['email']),
            'password' => Hash::make($data['password']),
            'enabled' => 1,
            'roles' => 'a:1:{i:0;s:12:"ROLE_SCANNER";}',
            'slug' => Str::slug($data['username'])
        ]);

        return Scanner::create([
            'name' => $data['name'],
            'user_id' => $user->id,
            'organizer_id' => auth()->user()->organizer_id,
        ]);
    }

}
