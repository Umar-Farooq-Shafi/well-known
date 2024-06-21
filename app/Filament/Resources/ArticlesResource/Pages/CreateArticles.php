<?php

namespace App\Filament\Resources\ArticlesResource\Pages;

use App\Filament\Resources\ArticlesResource;
use App\Models\HelpCenterArticle;
use App\Models\HelpCenterArticleTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateArticles extends CreateRecord
{
    protected static string $resource = ArticlesResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $helpCenterArticle = HelpCenterArticle::create([
            'category_id' => $data['category_id'],
            'hidden' => 0,
            'featured' => 0
        ]);

        HelpCenterArticleTranslation::create([
            'translatable_id' => $helpCenterArticle->id,
            'title' => data_get($data, 'title-en'),
            'slug' => Str::slug(data_get($data, 'title-en')),
            'content' => data_get($data, 'content-en'),
            'tags' => data_get($data, 'tags-en'),
            'locale' => 'en',
        ]);

        if (data_get($data, 'title-fr')) {
            HelpCenterArticleTranslation::create([
                'translatable_id' => $helpCenterArticle->id,
                'title' => data_get($data, 'title-fr'),
                'slug' => Str::slug(data_get($data, 'title-fr')),
                'content' => data_get($data, 'content-fr'),
                'tags' => data_get($data, 'tags-fr'),
                'locale' => 'fr',
            ]);
        }

        if (data_get($data, 'title-es')) {
            HelpCenterArticleTranslation::create([
                'translatable_id' => $helpCenterArticle->id,
                'title' => data_get($data, 'title-es'),
                'slug' => Str::slug(data_get($data, 'title-es')),
                'content' => data_get($data, 'content-es'),
                'tags' => data_get($data, 'tags-es'),
                'locale' => 'es',
            ]);
        }

        if (data_get($data, 'title-ar')) {
            HelpCenterArticleTranslation::create([
                'translatable_id' => $helpCenterArticle->id,
                'title' => data_get($data, 'title-ar'),
                'slug' => Str::slug(data_get($data, 'title-ar')),
                'content' => data_get($data, 'content-ar'),
                'tags' => data_get($data, 'tags-ar'),
                'locale' => 'fr',
            ]);
        }

        return $helpCenterArticle;
    }
}
