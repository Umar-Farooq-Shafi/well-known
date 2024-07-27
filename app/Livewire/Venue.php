<?php

namespace App\Livewire;

use App\Models\Venue as ModelsVenue;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Venue extends Component
{
    use WithPagination;

    public $created_at = 'desc';

    public $name;

    public $country;

    public function search()
    {
        $this->resetPage();
    }

    #[Title("Venue | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $venues = ModelsVenue::query()
            ->with([
                'venueTranslations' => function ($query) {
                    $query
                        ->when(
                            $this->name,
                            fn($query, $name) => $query->where('name', 'like', '%' . $name . '%')
                        );
                },
                'venueType',
                'country' => function ($query) {
                    $query->when(
                        $this->country,
                        fn($query) => $query->where('id', $this->country)
                    );
                }
            ])
            ->orderBy("created_at", $this->created_at)
            ->paginate(8);


        return view('livewire.venue', [
            'venues' => $venues,
            'total_venue' => ModelsVenue::count(),
        ]);
    }
}
