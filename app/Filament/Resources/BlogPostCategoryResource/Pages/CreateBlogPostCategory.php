<?php

namespace App\Filament\Resources\BlogPostCategoryResource\Pages;

use App\Filament\Resources\BlogPostCategoryResource;
use App\Models\BlogPostCategory;
use App\Models\BlogPostCategoryTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateBlogPostCategory extends CreateRecord
{
    protected static string $resource = BlogPostCategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $helpCenterCategory = BlogPostCategory::create([
            'hidden' => 0,
        ]);

        foreach ($data as $name => $value) {
            if ($value && $name !== 'icon') {
                BlogPostCategoryTranslation::create([
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
