<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\UpdateReviewRequest;
use App\Http\Resources\Reservation\ReviewResource;
use App\Models\Property\Property;
use App\Models\Reservation\Review;
use App\Services\Property\PropertyService;
use App\Services\Reservation\ReviewService;

class ReviewController extends Controller {
    private readonly ReviewService $reviewService;
    private readonly PropertyService $propertyService;
    public function __construct(
        ReviewService $reviewService,
        PropertyService $propertyService
    ) {
        $this->reviewService = $reviewService;
        $this->propertyService = $propertyService;
    }

    public function getAllPropertyReviews(Property $property) {
        $reviews = $this->reviewService->getAllPropertyReviews($property);

        return ReviewResource::collection($reviews);
    }

    public function show(Review $review) {
        $review->load('reservation');

        $this->authorize('view', $review);

        return new ReviewResource($review);
    }

    public function update(UpdateReviewRequest $request, Review $review) {
        $this->authorize('update', $review);

        $review->loadMissing('reservation:id,property_id');

        $property = $review->reservation->relationLoaded('property')
            ? $review->reservation->property
            : $review->reservation->load('property')->property;

        $oldReviewData = [
            'stars' => (float) ($review->stars ?? 0),
        ];

        $newReviewData = $request->validated();

        $updated = $this->reviewService->updateReview($review, $newReviewData);

        $this->propertyService->replaceReviewRating($property, $oldReviewData, $newReviewData);

        return new ReviewResource($updated);
    }

    public function destroy(Review $review) {
        $this->authorize('delete', $review);

        $review->loadMissing('reservation:id,property_id');

        $property = $review->reservation->relationLoaded('property')
            ? $review->reservation->property
            : $review->reservation->load('property')->property;

        $reviewData = [
            'stars' => (float) ($review->stars ?? 0),
        ];

        $this->propertyService->removeReviewStats($property, $reviewData);

        $this->reviewService->deleteReview($review);

        return response()->noContent();
    }
}
