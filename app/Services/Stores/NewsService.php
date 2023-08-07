<?php

namespace App\Services\Stores;

use App\Models\News;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewsService
{
    public function create($data){
        $auth= Auth::user();
        News::create([
            'title'=>$data['title'],
            'sub_title'=>$data['sub_title'],
            'type'=>$data['type'],
            'content'=>$data['content'],
            'operator'=>$auth->name
        ]);
    }

    public function update($news_id,$data){
        $news =  News::find($news_id);
        $news->fill($data);
        $news->save();
    }

    public function pageList($data)
    {
        $Builder = new News();

        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('title', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('sub_title', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('operator', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('content', 'like', '%' . $data['keyword'] . '%');
            });
        }

        return $Builder->orderBy('created_at','desc')
        ->orderBy('updated_at','desc')->paginate($data['per_page']);

    }

}
