<?php

namespace App\Livewire;

use App\Models\Venue as ModelsVenue;
use App\Models\VenueType;
use Illuminate\Database\Eloquent\Builder;
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

    public $minSeatedGuests;

    public $maxSeatedGuests;

    public $minStandingGuests;

    public $maxStandingGuests;

    public function search()
    {
        $this->resetPage();
    }

    #[Title("Venues | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $venueTypes = VenueType::query()
            ->whereHas('venues')
            ->get();

        $venues = ModelsVenue::query()
            ->when(
                $this->minSeatedGuests,
                fn (Builder $query, $minSeatedGuests)
                    => $query->where('seatedguests', '>=', $minSeatedGuests)
            )
            ->when(
                $this->maxSeatedGuests,
                fn (Builder $query, $maxSeatedGuests)
                    => $query->where('seatedguests', '<=', $maxSeatedGuests)
            )
            ->when(
                $this->minStandingGuests,
                fn (Builder $query, $minStandingGuests)
                    => $query->where('standingguests', '>=', $minStandingGuests)
            )
            ->when(
                $this->maxStandingGuests,
                fn (Builder $query, $maxStandingGuests)
                    => $query->where('standingguests', '<=', $maxStandingGuests)
            )
            ->when(
                $this->name,
                function (Builder $query, $name) {
                    $query
                        ->whereHas(
                            'venueTranslations',
                            function (Builder $query) use ($name) {
                                $query->where('name', 'like', '%' . $name . '%');                            }
                        );
                }
            )
            ->when(
                $this->country,
                function (Builder $query, $country) {
                    $query->whereHas(
                        'country',
                        function (Builder $query) use ($country) {
                            $query->where('id', $country);
                        }
                    );
                }
            )
            ->when(
                count($this->selectedVenueTypes) > 0,
                function (Builder $query) {
                    $query->whereHas(
                        'venueType',
                        function (Builder $query) {
                            $query->whereIn('id', $this->selectedVenueTypes);
                        }
                    );
                }
            )
            ->with([
                'venueTranslations',
                'venueType',
                'country'
            ])
            ->orderBy("created_at", $this->created_at);

        return view('livewire.venues', [
            'venues' => $venues->paginate(8),
            'total_venue' => $venues->count(),
            'venueTypes' => $venueTypes,
        ]);
    }
}
