<?php

namespace App\Traits;

trait RatingTrait
{
    public function calculateAverageRating()
    {
        $reviews = $this->eventTranslation->event->reviews;

        if ($reviews->isEmpty()) {
            return 0;
        }

        $sumOfRatings = $reviews->sum('rating');

        $numberOfReviews = $reviews->count();

        $averageRating = $sumOfRatings / $numberOfReviews;

        return round($averageRating, 2);
    }

    public function calculateRatingPercentages()
    {
        $reviews = $this->eventTranslation->event->reviews;

        if ($reviews->isEmpty()) {
            return [
                '5' => 0,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0,
            ];
        }

        $totalReviews = $reviews->count();

        $ratingCounts = [
            '5' => 0,
            '4' => 0,
            '3' => 0,
            '2' => 0,
            '1' => 0,
        ];

        foreach ($reviews as $review) {
            $ratingCounts[$review->rating]++;
        }

        $ratingPercentages = [];
        foreach ($ratingCounts as $rating => $count) {
            $ratingPercentages[$rating] = ($count / $totalReviews) * 100;
        }

        return $ratingPercentages;
    }
}
