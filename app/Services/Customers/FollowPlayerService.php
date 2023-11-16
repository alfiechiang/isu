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
        $total_count=3;
        $updated_at=$data['updated_at'];
        $res=  FollowPlayer::where('review',1)->where('updated_at','>',$updated_at)->limit($total_count)->get();
        if( $total_count-$res->count()>0){
            $early_time=FollowPlayer::where('review',1)->orderBy('updated_at')->limit(1)->first()->updated_at;
            $residue_count=$total_count-$res->count();
            $res2=FollowPlayer::where('review',1)->where('updated_at','>=',$early_time)->limit($residue_count)->get();
            $res = $res2->merge($res);
        }

        return $res;
    }

    public function stronghold(){

        $north=FollowPlayer::where('area', 'LIKE', '%'.'north'.'%')->where('review', true)->get()->count();
        $middle=FollowPlayer::where('area', 'LIKE', '%'.'middle'.'%')->where('review', true)->get()->count();
        $south=FollowPlayer::where('area', 'LIKE', '%'.'south'.'%')->where('review', true)->get()->count();
        $east=FollowPlayer::where('area', 'LIKE', '%'.'east'.'%')->where('review', true)->get()->count();
        $out_island=FollowPlayer::where('area', 'LIKE', '%'.'out_island'.'%')->where('review', true)->get()->count();
        $oversea=FollowPlayer::where('area', 'LIKE', '%'.'oversea'.'%')->where('review', true)->get()->count();
        $total=FollowPlayer::where('review', true)->get()->count();
        return [
            'north'=>$north,
            'middle'=>$middle,
            'south'=>$south,
            'east'=>$east,
            'out_island'=>$out_island,
            'oversea'=>$oversea,
            'total'=>$total
        ];

    }

   


}
