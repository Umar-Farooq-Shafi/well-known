<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use App\Models\MenuElementTranslation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        foreach ($this->record->menuTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $data['name-ar'] = $translation->name;
                $data['header-ar'] = $translation->header;
            }

            if ($translation->locale === 'en') {
                $data['name-en'] = $translation->name;
                $data['header-en'] = $translation->header;
            }

            if ($translation->locale === 'fr') {
                $data['name-fr'] = $translation->name;
                $data['header-fr'] = $translation->header;
            }

            if ($translation->locale === 'es') {
                $data['name-es'] = $translation->name;
                $data['header-es'] = $translation->header;
            }
        }

        return $data;
    }

    public function afterSave()
    {
        $data = $this->data;

        foreach ($this->record->menuTranslations as $translation) {
            if ($translation->locale === 'ar') {
                $translation->name = $data['name-ar'];
                $translation->header = $data['header-ar'];
                $translation->save();
            }

            if ($translation->locale === 'en') {
                $translation->name = $data['name-en'];
                $translation->header = $data['header-en'];
                $translation->save();
            }

            if ($translation->locale === 'fr') {
                $translation->name = $data['name-fr'];
                $translation->header = $data['header-fr'];
                $translation->save();
            }

            if ($translation->locale === 'es') {
                $translation->name = $data['name-es'];
                $translation->header = $data['header-es'];
                $translation->save();
            }
        }

        foreach ($data['menuElements'] as $k => $menuElement) {
            $menuElementTrans = MenuElementTranslation::find($menuElement['id']);

            if ($menuElementTrans->locale === 'ar') {
                $menuElementTrans->label = $menuElement['label-ar'];
            }

            if ($menuElementTrans->locale === 'en') {
                $menuElementTrans->label = $menuElement['label-en'];
            }

            if ($menuElementTrans->locale === 'fr') {
                $menuElementTrans->label = $menuElement['label-fr'];
            }

            if ($menuElementTrans->locale === 'es') {
                $menuElementTrans->label = $menuElement['label-es'];
            }

            $menuElementTrans->save();
        }
    }

}
