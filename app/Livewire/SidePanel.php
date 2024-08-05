<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Event;
use App\Models\HomepageHeroSetting;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Livewire\Component;

class SidePanel extends Component
{

    public function render(): ViewContract
    {
        $homepageHeroSetting = HomepageHeroSetting::first();
        $sliderContents = [];

        if ($homepageHeroSetting->content === 'events') {
            $sliderContents = Event::with([
                'eventTranslations' => function ($query) {
                    $query->where('locale', App::getLocale());
                },
            ])
                ->where('completed', false)
                ->where('isonhomepageslider_id', $homepageHeroSetting->id)
                ->get();
        }

        return View::make(
            view: 'livewire.side-panel',
            data: [
                'sliderContents' => $sliderContents,
            ]
        );
    }
}
