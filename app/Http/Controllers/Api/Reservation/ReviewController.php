<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\UpdateReviewRequest;
use App\Http\Resources\Reservation\ReviewResource;
use App\Models\Reservation\Review;
use App\Services\Property\PropertyService;
use App\Services\Reservation\ReviewService;

class ReviewController extends Controller {
    public function __construct(
        private readonly ReviewService $reviewService,
        private readonly PropertyService $propertyService,
    ) {
    }

    public function getAllPropertyReviews($propertyId) {
        $reviews = $this->reviewService->getAllPropertyReviews($propertyId);

        return ReviewResource::collection($reviews);
    }

    public function show(Review $review) {
        $review->load('reservation');

        $this->authorize('view', $review);

        return new ReviewResource($review);
    }

    public function update(UpdateReviewRequest $request, Review $review) {
        $this->authorize('update', $review);

        // Ensure we have reservation->property_id available
        $review->loadMissing('reservation:id,property_id');

        $propertyId = (int) $review->reservation->property_id;

        // Old rating payload (match your service key handling; stars is fine now)
        $oldReviewData = [
            'stars' => (float) ($review->stars ?? 0),
        ];

        $newReviewData = $request->validated();

        $updated = $this->reviewService->updateReview($review->id, $newReviewData);

        // Update property stats: replace old rating with new rating
        $this->propertyService->replaceReviewRating($propertyId, $oldReviewData, $newReviewData);

        return new ReviewResource($updated);
    }

    public function destroy(Review $review) {
        $this->authorize('delete', $review);

        $review->loadMissing('reservation:id,property_id');

        $propertyId = (int) $review->reservation->property_id;

        // Rating payload for removal
        $reviewData = [
            'stars' => (float) ($review->stars ?? 0),
        ];

        // Remove stats first (or after) — either is fine as long as you still have old stars
        $this->propertyService->removeReviewStats($propertyId, $reviewData);

        $this->reviewService->deleteReview($review->id);

        return response()->noContent();
    }
}
