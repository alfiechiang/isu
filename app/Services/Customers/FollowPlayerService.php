<?php

namespace App\Services\Customers;

use App\Models\County;
use App\Models\FollowPlayer;

class FollowPlayerService
{

    public function pageList($data)
    {

        $Builder = new FollowPlayer();

        if(isset($data['region'])){
            $Builder=$Builder->where('area', 'LIKE', '%'.$data['region'].'%');
        }
        
        return $Builder->where('review', true)->orderBy('created_at', 'desc')->paginate($data['per_page']);
    }

    public function findone($follow_id)
    {
      return  FollowPlayer::find($follow_id);
    }


    public function findothers($data){
        $updated_at=$data['updated_at'];
        return  FollowPlayer::where('review',1)->where('updated_at','>',$updated_at)->limit(3)->get();
    }

    public function stronghold(){

        $north=FollowPlayer::where('area', 'LIKE', '%'.'north'.'%')->where('review', true)->get()->count();
        $middle=FollowPlayer::where('area', 'LIKE', '%'.'middle'.'%')->where('review', true)->get()->count();
        $south=FollowPlayer::where('area', 'LIKE', '%'.'south'.'%')->where('review', true)->get()->count();
        $east=FollowPlayer::where('area', 'LIKE', '%'.'east'.'%')->where('review', true)->get()->count();
        $out_island=FollowPlayer::where('area', 'LIKE', '%'.'out_island'.'%')->where('review', true)->get()->count();
        $oversea=FollowPlayer::where('area', 'LIKE', '%'.'oversea'.'%')->where('review', true)->get()->count();

        return [
            'north'=>$north,
            'middle'=>$middle,
            'south'=>$south,
            'east'=>$east,
            'out_island'=>$out_island,
            'oversea'=>$oversea
        ];

    }

   


}
