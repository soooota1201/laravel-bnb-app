<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Booking extends Model
{
    public function bookable()
    {
      return $this->belongsTo(Bookable::class);
    }

    public function review()
    {
      return $this->hasOne(Review::class);
    }

    public function scopeBetweenDates(Builder $query, $from, $to)
    {
      return $query->where('to', '>=' , $from)->where('from', '<=', $to);
    }

  /**
   * こっから下意味わからん
   * 要復習
   * 
   * staticメソッド
   * 静的メソッドは何のためにあるかといえば、集合に含まれるすべてのオブジェクトに共通した処理(公理ともいう)を記述するため
   * boot()
   * bootメソッドをoverwriteしたイベントのフック
   * Eloquentモデルでは、自身の初期化時にboot()メソッドが呼ばれます。このメソッド内で各イベントが発火された際の処理を記述します。
   * ==============================================================
   */

    //review_keyを使って、BookingをFetchする(custom model methods)
    public static function findByReviewKey(string $reviewKey): ?Booking
    {
      /**
       * eager load
       * クエリ数の削減に繋がる
       */
      return static::where('review_key', $reviewKey)->with('bookable')->get()->first();
    }

    protected static function boot()
    {
      parent::boot(); //←形式的に書く必要がある
      //Bookingクラスが作られた時は常にreview_keyが生成される
      static::creating(function($booking) {
        $booking->review_key = Str::uuid();
      });
    }
}
