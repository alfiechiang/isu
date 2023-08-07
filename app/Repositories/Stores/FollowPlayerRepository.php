<?php

namespace App\Repositories\Stores;

use App\Models\FollowPlayer;

class FollowPlayerRepository {


    public function adminRoleCreate($data){
       FollowPlayer::create([
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'copyright'=>$data['copyright'],
            'artist'=>$data['artist'],
            'link_url'=>$data['link_url'],
            'area'=>$data['area'],
            'content'=>$data['content']
       ]);
  
    }

    public function storeRoleCreate($data){
        FollowPlayer::create([
            'store_uid'=>$data['store_uid'],
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'copyright'=>$data['copyright'],
            'artist'=>$data['artist'],
            'link_url'=>$data['link_url'],
            'area'=>$data['area'],
            'content'=>$data['content']
       ]);
       
    }

    public function counterRoleCreate($data){
        FollowPlayer::create([
            'store_uid'=>$data['store_uid'],
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'copyright'=>$data['copyright'],
            'artist'=>$data['artist'],
            'link_url'=>$data['link_url'],
            'area'=>$data['area'],
            'content'=>$data['content'],
            'review'=>false
       ]);
       
    }

}
