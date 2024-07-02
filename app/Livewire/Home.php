<?php

namespace App\Livewire;

use App\Models\Audience;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $audiences = Audience::with([
            'audienceTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            }
        ])->get();

        return view('livewire.home')->with('audiences', $audiences);
    }
}
