<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bookable extends Model
{
    protected $fillable = ['from', 'to']; //マスアサイメント

    public function bookings() {
      return $this->hasMany(Booking::class);
    }
    public function review() {
      return $this->hasMany(Review::class);
    }

    public function availableFor($from, $to):bool
    {
      return 0 == $this->bookings()->betweenDates($from, $to)->count();
    }
}
