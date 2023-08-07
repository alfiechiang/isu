<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use App\Services\Stores\FollowPlayerService;
use App\Services\Stores\NewsService;
use Exception;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    protected $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    public function create(Request $request){
        try {
            $this->newsService->create($request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
      
    }

    public function update(Request $request, $news_id){
        try {
            $this->newsService->update($news_id,$request->all());
            return Response::format(200, [], '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    
    }

    public function list(Request $request){
        try {
            $res=$this->newsService->pageList($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }


}
