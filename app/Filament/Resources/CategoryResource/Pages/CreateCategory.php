<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\Category;
use App\Models\CategoryTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $size = Storage::disk('public')->size($data['image_name']);
        $mimetype = File::mimeType(Storage::disk('public')->path($data['image_name']));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path($data['image_name']));

        $country = Category::create([
            'icon' => $data['icon'],
            'hidden' => 0,
            'image_name' => last(explode('/', $data['image_name'])),
            'image_size' => $size,
            'image_mime_type' => $mimetype,
            'featured' => 1,
            'image_original_name' => $data['image_original_name'],
            'image_dimensions' => $image->width() . "," . $image->height(),
            'featuredorder' => $data['featuredorder']
        ]);

        foreach (Arr::except($data, ['icon', 'featuredorder', 'image_name', 'image_original_name']) as $name => $value) {
            if ($value !== null) {
                CategoryTranslation::create([
                    'translatable_id' => $country->id,
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

        return $country;
    }
}
