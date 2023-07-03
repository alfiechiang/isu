<?php

namespace App\Http;


class Response
{

    public static function format($code, $data, $message)
    {

        $json = [];
        $json['code'] = $code;
        $json['message'] = $message;
        $json['data'] = $data;
        return response()->json($json);
    }

    public static function success()
    {
        $json = [];
        $json['code'] = 200;
        $json['message'] = '請求成功';
        $json['data'] = [];
        return response()->json($json);
    }

    public static function error()
    {
        $json = [];
        $json['code'] = 40001;
        $json['message'] = '系統錯誤';
        $json['data'] = [];
        return response()->json($json);
    }

    public static function errorFormat($e)
    {
        $json = [];
        $json['code'] = 40001;
        $json['message'] = $e;
        $json['data'] = [];
        return response()->json($json);
    }


}
