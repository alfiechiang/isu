<?php

namespace App\Http\Controllers\Customers;

use Illuminate\Http\Request;
use App\Http\Response;
use App\Services\Customers\NewsService;
use Exception;

class NewsController extends Controller
{

    protected NewsService $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }
    public function list(Request $request)
    {
        try {
            $res = $this->newsService->list($request->all());
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function findone($news_id)
    {
        try {
            $res = $this->newsService->findone($news_id);
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }

    public function stronghold(Request $request)
    {
        try {
            $res = $this->newsService->stronghold();
            return Response::format(200, $res, '請求成功');
        } catch (Exception $e) {
            return Response::errorFormat($e);
        }
    }
}
