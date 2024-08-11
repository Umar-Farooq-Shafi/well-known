<?php

namespace App\Livewire;

use App\Models\EventTranslation;
use App\Traits\RatingTrait;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use RalphJSmit\Livewire\Urls\Facades\Url;
use WireUi\Traits\WireUiActions;

class AddReview extends Component
{
    use WireUiActions;
    use RatingTrait;

    public ?EventTranslation $eventTranslation = null;

    public $event;

    #[Validate('required')]
    public $rating;

    #[Validate('required|min:10')]
    public $headline;

    #[Validate('required|min:25')]
    public $details;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        if (!auth()->user()->hasRole('ROLE_ATTENDEE')) {
            abort(403);
        }

        $this->event = $this->eventTranslation->event;

        if ($this->event->reviews()->where('user_id', auth()->id())->exists()) {
            $this->redirect(route('filament.admin.resources.reviews.index'));
        }
    }

    #[Title("Add Review | 'Aafno Ticket Nepal'")]
    public function submit(): void
    {
        $this->validate();

        $this->event->reviews()->create([
            'headline' => $this->headline,
            'rating' => $this->rating,
            'visible' => 1,
            'user_id' => auth()->id(),
            'slug' => Str::slug($this->headline),
            'details' => $this->details,
        ]);

        $this->reset(['headline', 'rating', 'details']);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Submit!',
            'description' => 'Your review has been submitted.',
        ]);

        $this->redirect(route(Url::previous()));
    }

    public function render()
    {
        $averageRating = $this->calculateAverageRating();
        $ratingPercentages = $this->calculateRatingPercentages();

        $reviews = $this->event->reviews()->count();

        return view('livewire.add-review', [
            'averageRating' => $averageRating,
            'ratingPercentages' => $ratingPercentages,
            'reviews' => $reviews,
        ]);
    }
}
