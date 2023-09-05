<?php

namespace App\Services\Customers;

use App\Models\News;
use Illuminate\Support\Facades\DB;

class NewsService
{

    public function list($data)
    {
        $Builder = new News();
        if (isset($data['type'])) {
            $Builder = $Builder->where('type', $data['type']);
        }
        return $Builder->orderBy('created_at','desc')->paginate($data['per_page']);
    }

    public function findothers($data){
        $updated_at=$data['updated_at'];
        return  News::where('updated_at','>',$updated_at)->limit(3)->get();
    }

    public function findone($news_id)
    {   
        return News::find($news_id);
    }

    public function stronghold()
    {
       return News::select('type', DB::raw('count(type) as total'))->groupBy('type')->get();
    }
}
