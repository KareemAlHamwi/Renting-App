<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\UpdateReviewRequest;
use App\Http\Resources\Reservation\ReviewResource;
use App\Services\Reservation\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    private ReviewService $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }

    public function getAllPropertyReviews($propertyId) {
        $reviews = $this->reviewService->getAllPropertyReviews($propertyId);

        return ReviewResource::collection($reviews);
    }

    public function show($id) {
        $review = $this->reviewService->getReview($id);

        return new ReviewResource($review);
    }

    public function update(UpdateReviewRequest $request, $id) {
        $data = $request->all();
        $review = $this->reviewService->updateReview($id, $data);

        return new ReviewResource($review);
    }

    public function destroy($id) {
        $this->reviewService->deleteReview($id);
        return response()->noContent();
    }
}
