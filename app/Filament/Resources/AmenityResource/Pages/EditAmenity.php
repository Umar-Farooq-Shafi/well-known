<?php

namespace App\Filament\Resources\AmenityResource\Pages;

use App\Filament\Resources\AmenityResource;
use App\Models\AmenityTranslation;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditAmenity extends EditRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = AmenityResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->amenityTranslations as $translation) {
            if ($translation->locale === 'ar')
                $data['name-ar'] = $translation->name;

            if ($translation->locale === 'en')
                $data['name-en'] = $translation->name;

            if ($translation->locale === 'fr')
                $data['name-fr'] = $translation->name;

            if ($translation->locale === 'es')
                $data['name-es'] = $translation->name;
        }

        return $data;
    }

    /**
     * @throws Halt
     */
    public function beforeSave(): void
    {
        foreach ($this->data as $name => $value) {
            if ($value && $name !== 'icon') {
                $amTrans = AmenityTranslation::query()->where('slug', Str::slug($value))
                    ->whereNot('translatable_id', $this->record->id)
                    ->exists();

                if ($amTrans) {
                    Notification::make()
                        ->title('Duplicate')
                        ->danger()
                        ->body($value . " already exists.")
                        ->send();

                    $this->halt();
                }
            }
        }
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'icon' => $data['icon']
        ]);

        foreach ($record->amenityTranslations as $translation) {
            $locale = $translation->locale;
            $nameKey = 'name-' . $locale;
            $nameValue = $data[$nameKey] ?? '';

            if (in_array($locale, ['ar', 'en', 'fr', 'es'])) {
                $baseSlug = Str::slug($nameValue, language: $locale);
                $slug = $baseSlug;
                $counter = 1;

                while ($record->amenityTranslations()
                    ->where('slug', $slug)
                    ->where('id', '!=', $translation->id)
                    ->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $translation->name = $nameValue;
                $translation->slug = $slug;

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
