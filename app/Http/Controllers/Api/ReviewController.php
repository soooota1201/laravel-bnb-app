<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ReviewResource;
use App\Review;

class ReviewController extends Controller
{
    public function show($id)
    {
      return new ReviewResource(Review::findOrFail($id));
    }

    /**
     * 要復習21/02/14
     */
    public function store(Request $request)
    {
      $data = $request->validate([
        'id' => 'required|size:36|unique:reviews',//uuidの長さ
        'content' => 'required|min:2',
        'rating' => 'required|in:1,2,3,4,5'
      ]);

      $booking = Booking::findByReviewKey($data['id']);

      if(null === $booking) {
        return abort(404);
      }

      $booking->review_key = '';
      $booking->save();

      $review = Review::make($data);
      $review->booking_id = $booking->id;
      $review->bookable_id = $booking->bookable_id;
      $review->save();

      return new ReviewResource($review);
    }
}
