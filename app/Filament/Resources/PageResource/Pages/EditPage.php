<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->pageTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['title-ar'] = $translation->title;
                $data['content-ar'] = $translation->content;
            }

            if ($translation->locale === 'en') {
                $data['title-en'] = $translation->title;
                $data['content-en'] = $translation->content;
            }

            if ($translation->locale === 'fr') {
                $data['title-fr'] = $translation->title;
                $data['content-fr'] = $translation->content;
            }

            if ($translation->locale === 'es') {
                $data['title-es'] = $translation->title;
                $data['content-es'] = $translation->content;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return $record;
    }

    protected function afterSave(): void
    {
        $data = $this->data;

        foreach ($this->record->pageTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->title = $data['title-ar'];
                $translation->content = $data['content-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->title = $data['title-en'];
                $translation->content = $data['content-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->title = $data['title-fr'];
                $translation->content = $data['content-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->title = $data['title-es'];
                $translation->content = $data['content-es'];
                $translation->save();
            }
        }
    }

}
