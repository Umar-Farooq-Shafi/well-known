<?php

namespace App\Filament\Resources\ArticlesResource\Pages;

use App\Filament\Resources\ArticlesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditArticles extends EditRecord
{
    protected static string $resource = ArticlesResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->helpCenterTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['title-ar'] = $translation->title;
                $data['content-ar'] = $translation->content;
                $data['tags-ar'] = $translation->tags;
            }

            if ($translation->locale === 'en') {
                $data['title-en'] = $translation->title;
                $data['content-en'] = $translation->content;
                $data['tags-en'] = $translation->tags;
            }

            if ($translation->locale === 'fr') {
                $data['title-fr'] = $translation->title;
                $data['content-fr'] = $translation->content;
                $data['tags-fr'] = $translation->tags;
            }

            if ($translation->locale === 'es') {
                $data['title-es'] = $translation->title;
                $data['content-es'] = $translation->content;
                $data['tags-es'] = $translation->tags;
            }
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update([
            'category_id' => $data['category_id'],
        ]);

        foreach ($record->helpCenterTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->title = $data['title-ar'];
                $translation->slug = Str::slug($data['title-ar'] ?? '');
                $translation->content = $data['content-ar'];
                $translation->tags = $data['tags-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->title = $data['title-en'];
                $translation->slug = Str::slug($data['title-en'] ?? '');
                $translation->content = $data['content-en'];
                $translation->tags = $data['tags-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->title = $data['title-fr'];
                $translation->slug = Str::slug($data['title-fr'] ?? '');
                $translation->content = $data['content-fr'];
                $translation->tags = $data['tags-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->title = $data['title-es'];
                $translation->slug = Str::slug($data['title-es'] ?? '');
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
