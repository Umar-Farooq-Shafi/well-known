<?php

namespace App\Filament\Resources\BlogPostCategoryResource\Pages;

use App\Filament\Resources\BlogPostCategoryResource;
use App\Traits\FilamentNavigationTrait;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EditBlogPostCategory extends EditRecord
{
    use FilamentNavigationTrait;

    protected static string $resource = BlogPostCategoryResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->blogPostCategoryTranslations as $translation) {
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

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        foreach ($record->blogPostCategoryTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->slug = Str::slug($data['name-ar'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->slug = Str::slug($data['name-en'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->slug = Str::slug($data['name-fr'] ?? '');
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->slug = Str::slug($data['name-es'] ?? '');
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
