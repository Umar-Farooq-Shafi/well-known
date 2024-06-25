<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->blogPostTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
                $data['content-ar'] = $translation->content;
                $data['tags-ar'] = $translation->tags;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
                $data['content-en'] = $translation->content;
                $data['tags-en'] = $translation->tags;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
                $data['content-fr'] = $translation->content;
                $data['tags-fr'] = $translation->tags;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
                $data['content-es'] = $translation->content;
                $data['tags-es'] = $translation->tags;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $size = Storage::disk('public')->size($data['image_name']);
        $mimetype = File::mimeType(Storage::disk('public')->path($data['image_name']));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path($data['image_name']));

        $record->update([
            'category_id' => $data['category_id'],
            'readtime' => $data['readtime'],
            'image_name' => last(explode('/', $data['image_name'])),
            'image_size' => $size,
            'image_mime_type' => $mimetype,
            'image_original_name' => $data['image_original_name'],
            'image_dimensions' => $image->width() . "," . $image->height(),
        ]);

        foreach ($record->blogPostTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->content = $data['content-ar'];
                $translation->tags = $data['tags-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->content = $data['content-en'];
                $translation->tags = $data['tags-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->content = $data['content-fr'];
                $translation->tags = $data['tags-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->content = $data['content-es'];
                $translation->tags = $data['tags-es'];
                $translation->save();
            }
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
