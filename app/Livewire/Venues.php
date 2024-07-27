<?php

namespace App\Livewire;

use App\Models\Venue as ModelsVenue;
use App\Models\VenueType;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Venues extends Component
{
    use WithPagination;

    public $created_at = 'desc';

    public $name;

    public $country;

    public $selectedVenueTypes = [];

    public function search()
    {
        $this->resetPage();
    }

    #[Title("Venue | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $venueTypes = VenueType::query()
            ->whereHas('venues')
            ->get();

        $venues = ModelsVenue::query()
            ->with([
                'venueTranslations' => function ($query) {
                    $query
                        ->when(
                            $this->name,
                            fn($query, $name) => $query->where('name', 'like', '%' . $name . '%')
                        );
                },
                'venueType' => function ($query) {
                    $query->when(
                        count($this->selectedVenueTypes) > 0,
                        fn($query) => $query->where('venue_type_id', $this->selectedVenueTypes)
                    );
                },
                'country' => function ($query) {
                    $query->when(
                        $this->country,
                        fn($query) => $query->where('id', $this->country)
                    );
                }
            ])
            ->orderBy("created_at", $this->created_at)
            ->paginate(8);

        return view('livewire.venues', [
            'venues' => $venues,
            'total_venue' => ModelsVenue::count(),
            'venueTypes' => $venueTypes,
        ]);
    }
}
