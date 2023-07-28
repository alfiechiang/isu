<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Mail\VerifyEmail;
use App\Models\Otp;
use App\Services\Stores\HotelService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HotelController extends Controller
{

    protected $hotelService;

    public function __construct(HotelService $hotelService)
    {

        $this->hotelService = $hotelService;
    }

    public function create(Request $request){
        try {
            $this->hotelService->create($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
      
    }

    public function delete( $hotel_id){
        try {
            $this->hotelService->delete($hotel_id);
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
      
    }

    public function update(Request $request, $hotel_id){
        try {
            $this->hotelService->update($hotel_id,$request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    
    }

    public function batchImgUpdate(Request $request, $hotel_id){
        try {
            $this->hotelService->batchImgUpdate($hotel_id,$request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function list(Request $request){
        try {
            $res=$this->hotelService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function imgList( Request $request){
        try {
            $res=$this->hotelService->imgList( $request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }


}
