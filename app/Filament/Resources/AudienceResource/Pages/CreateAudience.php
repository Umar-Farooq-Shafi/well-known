<?php

namespace App\Filament\Resources\AudienceResource\Pages;

use App\Filament\Resources\AudienceResource;
use App\Models\Audience;
use App\Models\AudienceTranslation;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class CreateAudience extends CreateRecord
{
    protected static string $resource = AudienceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $img = $data['image_name'];

        $img = last(explode('/', $img));

        $size = Storage::disk('public')->size("audiences/" . $img);
        $mimetype = File::mimeType(Storage::disk('public')->path("audiences/" . $img));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path("audiences/" . $img));

        $audience = Audience::create([
            'hidden' => 0,
            'image_name' => $img,
            'image_size' => $size,
            'image_mime_type' => $mimetype,
            'image_original_name' => $data['image_original_name'],
            'image_dimensions' => $image->width() . "," . $image->height(),
        ]);

        foreach (Arr::except($data, ['image_name', 'image_original_name']) as $name => $value) {
            if ($value !== null) {
                AudienceTranslation::create([
                    'translatable_id' => $audience->id,
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

        return $audience;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
