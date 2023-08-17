<?php

namespace App\Services\Customers;
use App\Models\Recommend;

class RecommendService
{

    public function list($data)
    {
        $Builder = new Recommend();
        $time=date('H:i');
        switch($data['type']){
            case 'OPEN':
                $Builder = $Builder->where('open_start_time','<=', $time)
                ->where('open_end_time','>', $time);
                break;
            case 'CLOSE':
                $Builder = $Builder->where('open_start_time', '>', $time)
                ->orwhere('open_end_time', '<', $time);
                break;
        }

        return $Builder->get();
    }

    
}
