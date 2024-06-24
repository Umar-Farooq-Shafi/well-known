<?php

namespace App\Filament\Resources\VenueResource\Pages;

use App\Filament\Resources\VenueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;

class EditVenue extends EditRecord
{
    protected static string $resource = VenueResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->venueTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
                $data['content-ar'] = $translation->description;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
                $data['content-en'] = $translation->description;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
                $data['content-fr'] = $translation->description;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
                $data['content-es'] = $translation->description;
            }
        }

        return $data;
    }

    public function afterSave()
    {
        $data = $this->data;

        foreach ($this->record->venueTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->description = $data['content-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->description = $data['content-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->description = $data['content-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->description = $data['content-es'];
                $translation->save();
            }
        }

        foreach ($this->record->venueImages as $venueImage) {
            $img = last(explode('/', $venueImage->image_name));

            $size = Storage::disk('public')->size("venues/" . $img);
            $mimetype = File::mimeType(Storage::disk('public')->path("venues/" . $img));

            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path("venues/" . $img));

            $venueImage->update([
                'image_name' => $img,
                'image_size' => $size,
                'image_mime_type' => $mimetype,
                'image_dimensions' => $image->width() . "," . $image->height(),
            ]);
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
