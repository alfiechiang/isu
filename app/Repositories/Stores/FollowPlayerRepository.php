<?php

namespace App\Repositories\Stores;

use App\Models\FollowPlayer;
use Illuminate\Support\Facades\Auth;

class FollowPlayerRepository {


    public function adminRoleCreate($data){

       FollowPlayer::create([
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'copyright'=>$data['copyright'],
            'artist'=>$data['artist'],
            'link_url'=>$data['link_url'],
            'area'=>$data['area'],
            'content'=>$data['content'],
            'operator'=>(Auth::user())->email
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
            'content'=>$data['content'],
            'operator'=>(Auth::user())->email
       ]);
       
    }

    public function counterRoleCreate($data){

        $email=(Auth::user())->email;
        FollowPlayer::create([
            'store_uid'=>$data['store_uid'],
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'copyright'=>$data['copyright'],
            'artist'=>$data['artist'],
            'link_url'=>$data['link_url'],
            'area'=>$data['area'],
            'content'=>$data['content'],
            'review'=>false,
            'operator'=>$email,
            'creator'=>$email
       ]);
       
    }

}
