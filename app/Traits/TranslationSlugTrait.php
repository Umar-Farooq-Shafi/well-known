<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait TranslationSlugTrait
{
    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        self::updating(function($model){
            $model->slug = Str::slug($model->name);
        });
    }
}
