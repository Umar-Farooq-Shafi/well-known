<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $size = Storage::disk('public')->size($data['image_name']);

        $blogPost = BlogPost::create([
            'category_id' => $data['category_id'],
            'readtime' => $data['readtime'],
            'image_name' => explode('/', $data['image_name'])[1],
            'image_size' => $size,
            'image_mime_type',
            'image_original_name' => $data['image_original_name'],
            'image_dimensions',
            'hidden' => 0
        ]);

        BlogPostTranslation::create([
            'translatable_id' => $blogPost->id,
            'name' => data_get($data, 'name-en'),
            'slug' => Str::slug(data_get($data, 'name-en')),
            'content' => data_get($data, 'content-en'),
            'tags' => data_get($data, 'tags-en'),
            'locale' => 'en',
        ]);

        if (data_get($data, 'name-fr')) {
            BlogPostTranslation::create([
                'translatable_id' => $blogPost->id,
                'name' => data_get($data, 'name-fr'),
                'slug' => Str::slug(data_get($data, 'name-fr')),
                'content' => data_get($data, 'content-fr'),
                'tags' => data_get($data, 'tags-fr'),
                'locale' => 'fr',
            ]);
        }

        if (data_get($data, 'name-es')) {
            BlogPostTranslation::create([
                'translatable_id' => $blogPost->id,
                'name' => data_get($data, 'name-es'),
                'slug' => Str::slug(data_get($data, 'name-es')),
                'content' => data_get($data, 'content-es'),
                'tags' => data_get($data, 'tags-es'),
                'locale' => 'es',
            ]);
        }

        if (data_get($data, 'name-ar')) {
            BlogPostTranslation::create([
                'translatable_id' => $blogPost->id,
                'name' => data_get($data, 'name-ar'),
                'slug' => Str::slug(data_get($data, 'name-ar')),
                'content' => data_get($data, 'content-ar'),
                'tags' => data_get($data, 'tags-ar'),
                'locale' => 'fr',
            ]);
        }

        return $blogPost;
    }
}
