<?php

namespace App\Services\Customers;

use App\Models\County;
use App\Models\FollowPlayer;

class FollowPlayerService
{

    public function list($data)
    {

        $Builder = new FollowPlayer();
        return $Builder->where('area', 'LIKE', '%'.$data['region'].'%')->where('review', true)->get();
    }

    public function findone($follow_id)
    {
      return  FollowPlayer::find($follow_id);
    }

    public function stronghold(){

        return [
            'north'=>FollowPlayer::where('area', 'LIKE', '%'.'north'.'%')->get()->count(),
            'middle'=>FollowPlayer::where('area', 'LIKE', '%'.'middle'.'%')->get()->count(),
            'south'=>FollowPlayer::where('area', 'LIKE', '%'.'south'.'%')->get()->count(),
            'east'=>FollowPlayer::where('area', 'LIKE', '%'.'east'.'%')->get()->count(),
            'out_island'=>FollowPlayer::where('area', 'LIKE', '%'.'out_island'.'%')->get()->count(),
            'oversea'=>FollowPlayer::where('area', 'LIKE', '%'.'oversea'.'%')->get()->count()
        ];

    }

   


}
