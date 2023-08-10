<?php

namespace App\Services\Stores;

use App\Models\County;
use App\Models\Hotel;
use App\Models\HotelImage;
use Illuminate\Support\Facades\DB;

class HotelService
{
    public function create($data){

        Hotel::create([
            'hotel_name'=>$data['hotel_name'],
            'phone'=>$data['phone'],
            'house_phone'=>$data['house_phone'],
            'hotel_url'=>$data['hotel_url'],
            'hotel_desc'=>$data['hotel_desc'],
            'district'=>$data['district'],
            'country'=>$data['country'],
            'county'=>$data['county'],
            'google_map_url'=>$data['google_map_url'],
            'address'=>$data['address'],
        ]);
    }

    public function delete($hotel_id){
        $hotel=Hotel::find($hotel_id);
        $hotel->delete();
    }

    public function update($hotel_id,$data){
        $hotel =  Hotel::find($hotel_id);
        $hotel->fill($data);
        $hotel->save();
        return ['hotel_id'=>$hotel->id];
    }

    public function pageList($data){
        $Builder =new Hotel();
        if (!empty($data['keyword'])) {
            $Builder = $Builder->where(function ($query) use ($data) {
                $query->where('hotel_name', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('hotel_url', 'like', '%' . $data['keyword'] . '%')
                    ->orwhere('hotel_desc','like', '%' . $data['keyword'] . '%')
                    ->orwhere('county','like', '%' . $data['keyword'] . '%');
            });
        }
        return $Builder->orderBy('created_at','desc')->paginate($data['per_page']);
    }

    public function batchImgUpdate(int $hotel_id, array $img_data){
        $insertData=[];
        foreach($img_data as $item){
           $data=[
                'sort'=>$item['sort'],
                'img_url'=>$item['img_url'],
                'hotel_id'=>$hotel_id
           ];
           $insertData[]=$data;
        }
        DB::table("hotel_images")->insert($insertData);
    }

    public function imgList( $data){
        return HotelImage::where('hotel_id',$data['hotel_id'])->orderBy('sort','asc')->get();
    }

}
