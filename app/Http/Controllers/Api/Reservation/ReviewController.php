<?php

namespace App\Http\Controllers\Api\Reservation;

use App\Http\Controllers\Controller;
use App\Services\Reservation\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller {
    protected $reviewService;

    public function __construct(ReviewService $reviewService) {
        $this->reviewService = $reviewService;
    }


    public function store(Request $request) {
        $data = $request->validate([
            'stars'  => 'required|numeric|min:0|max:5',
            'review' => 'nullable|string',
        ]);

        $review = $this->reviewService->createReview($data);

        return response()->json($review, 201);
    }
    public function update(Request $request, $id) {
        $data = $request->validate([
            'stars'  => 'required|numeric|min:0|max:5',
            'review' => 'nullable|string',
        ]);

        $review = $this->reviewService->updateReview($id, $data);

        return response()->json($review);
    }


    public function destroy($id) {
        $this->reviewService->deleteReview($id);
        return response()->json(['message' => 'The values ​​were successfully deleted']);
    }


    public function show($id) {
        $review = $this->reviewService->getReview($id);
        return response()->json($review);
    }
}
