<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Bookable;
use App\Http\Resources\BookableReviewIndexResource;

class BookableReviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, Request $request)
    {
        // あればデータを取得、なければ404を返す
        $bookable = Bookable::findOrFail($id);

        // Bookableモデルで定義している(プロパティなら全ての関連するreviewのデータを取ってくる)。今回はそれよりもより詳細にデータをとるため、review()のメソッドにする(クエリビルダ)。
        //いらない情報まで取得するため、Requestで取得するデータを制御する
        // Requestファイルでの指定を下記コードを入れることで反映する
        return BookableReviewIndexResource::collection(
          $bookable->review()->latest()->get()
        );


    }
}
