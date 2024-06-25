<?php

namespace App\Filament\Resources\AudienceResource\Pages;

use App\Filament\Resources\AudienceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class EditAudience extends EditRecord
{
    protected static string $resource = AudienceResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->audienceTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $img = $data['image_name'];

        $img = last(explode('/', $img));

        $size = Storage::disk('public')->size("audiences/" . $img);
        $mimetype = File::mimeType(Storage::disk('public')->path("audiences/" . $img));

        $manager = new ImageManager(new Driver());
        $image = $manager->read(Storage::disk('public')->path("audiences/" . $img));

        $record->update([
            'hidden' => 0,
            'image_name' => $img,
            'image_size' => $size,
            'image_mime_type' => $mimetype,
            'image_original_name' => $data['image_original_name'],
            'image_dimensions' => $image->width() . "," . $image->height(),
        ]);

        foreach ($record->audienceTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
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
