<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\UpdateReviewRequest;
use App\Http\Resources\Reservation\ReviewResource;
use App\Models\Reservation\Review;
use App\Services\Reservation\ReviewService;

class ReviewController extends Controller {
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }

    public function getAllPropertyReviews($propertyId) {
        $this->authorize('viewAny', Review::class);

        $reviews = $this->reviewService->getAllPropertyReviews($propertyId);

        return ReviewResource::collection($reviews);
    }

    public function show($id) {
        $review = $this->reviewService->getReview($id);

        $this->authorize('view', $review);

        return new ReviewResource($review);
    }

    public function update(UpdateReviewRequest $request, $id) {
        $review = $this->reviewService->getReview($id);

        $this->authorize('update', $review);

        $review = $this->reviewService->updateReview($id, $request->validated());

        return new ReviewResource($review);
    }

    public function destroy($id) {
        $review = $this->reviewService->getReview($id);

        $this->authorize('delete', $review);

        $this->reviewService->deleteReview($id);

        return response()->noContent();
    }
}
