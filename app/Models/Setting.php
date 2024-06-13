<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'eventic_settings';

    protected $fillable = [
        'key',
        'value'
    ];

    protected $casts = [
        'key' => \App\Enums\Setting::class
    ];
}
