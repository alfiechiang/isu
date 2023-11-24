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

        $total_count=3;
        $updated_at=$data['updated_at'];
        $res=News::where('updated_at','>',$updated_at)->limit($total_count)->get();
        if( $total_count-$res->count()>0){
            $early_time=News::orderBy('updated_at')->limit(1)->first()->updated_at;
            $residue_count=$total_count-$res->count();
            $res2=News::where('updated_at','>=',$early_time)->limit($residue_count)->get();
            $res = $res2->merge($res);
        }

        return $res;

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
