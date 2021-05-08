<?php

namespace App\Http\Controllers\Api;

use App\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BookingByReviewShowResource;

class BookingByReviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($reviewKey, Request $request)
    {
    /**
     * abort(404)
     * 無効な検索が会った時に、404ページを表示してくれる
     * 
     * =========================================================================
     * 
     * ①データを下記コードで引っ張ってくる
     * return Booking::findByReviewKey($reviewKey) ?? abort(404);
     * ↑
     * (findByReviewKeyメソッドは、Bookingモデルに定義している。)
     * ↑
     * (bookingモデルへのreview_keyの追加もBookingモデルで行っている（Eloquent イベント？）)
     * 
     * ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
     * ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
     * ↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
     * 
     * ②BookingByReviewShowResourceでの定義を反映する
     * return new BookingByReviewShowResource (Booking::findByReviewKey($reviewKey)) ?? abort(404);
     * 
     */
        $booking = Booking::findByReviewKey($reviewKey);
    
        return $booking ? new BookingByReviewShowResource($booking) : abort(404);
    }
}
