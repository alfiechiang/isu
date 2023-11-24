<?php

namespace App\Services\Customers;

use App\Models\FollowPlayer;
use App\Models\Hotel;
use App\Models\News;
use App\Models\Recommend;

class HomeService
{

    public function list()
    {

        $news =News::orderBy('created_at','desc')->limit(8)->get();
        $follows =FollowPlayer::orderBy('created_at','desc')->limit(8)->get();
        $hotels =Hotel::with('images')->orderBy('created_at','desc')->limit(8)->get();
        $recommends=Recommend::orderBy('created_at','desc')->limit(8)->get();

        return [
            'news'=>$news,
            'follows'=>$follows,
            'hotels'=>$hotels,
            'recommends'=>$recommends
        ];
       
    }

   


  

   


}
