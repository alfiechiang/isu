<?php

namespace App\Services\Customers;

use App\Models\County;
use App\Models\FollowPlayer;

class FollowPlayerService
{

    public function list($data)
    {

        $Builder = new FollowPlayer();
        return $Builder->whereIn('area', $data['region'])->where('review', true)->get();
    }

    public function findone($follow_id)
    {
      return  FollowPlayer::find($follow_id);
    }

    public function stronghold(){

        $northAreas = County::where('region', 'north')->pluck('cn_name')->toArray();
        $middleAreas = County::where('region', 'middle')->pluck('cn_name')->toArray();
        $southAreas = County::where('region', 'south')->pluck('cn_name')->toArray();
        $EastAreas = County::where('region', 'east')->pluck('cn_name')->toArray();
        $OutIslandAreas = County::where('region', 'out_island')->pluck('cn_name')->toArray();
        $Oversea = County::where('region', 'oversea')->pluck('cn_name')->toArray();

        return [
            'north'=>FollowPlayer::whereIn('area',$northAreas)->get()->count(),
            'middle'=>FollowPlayer::whereIn('area',$middleAreas)->get()->count(),
            'south'=>FollowPlayer::whereIn('area',$southAreas)->get()->count(),
            'east'=>FollowPlayer::whereIn('area',$EastAreas)->get()->count(),
            'out_island'=>FollowPlayer::whereIn('area',$OutIslandAreas)->get()->count(),
            'oversea'=>FollowPlayer::whereIn('area',$Oversea)->get()->count()
        ];

    }

   


}
