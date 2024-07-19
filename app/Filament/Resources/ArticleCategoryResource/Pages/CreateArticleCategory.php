<?php

namespace App\Filament\Resources\ArticleCategoryResource\Pages;

use App\Filament\Resources\ArticleCategoryResource;
use App\Models\HelpCenterCategory;
use App\Models\HelpCenterCategoryTranslation;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateArticleCategory extends CreateRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = ArticleCategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $helpCenterCategory = HelpCenterCategory::create([
            'hidden' => 0,
            'icon' => $data['icon']
        ]);

        foreach ($data as $name => $value) {
            if ($value && $name !== 'icon') {
                HelpCenterCategoryTranslation::create([
                    'translatable_id' => $helpCenterCategory->id,
                    'name' => $value,
                    'slug' => Str::slug($value),
                    'locale' => match ($name) {
                        'name-es' => 'es',
                        'name-fr' => 'fr',
                        'name-ar' => 'ar',
                        default => 'en'
                    }
                ]);
            }
        }

        return $helpCenterCategory;
    }
}
