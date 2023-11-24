<?php

namespace App\Http\Controllers\Stores;

use App\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    public function upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $path = Storage::disk('s3')->put('customers', $request->file('file'));
            $url = Storage::disk('s3')->url($path);
        }

        return Response::format(200, ['url' => $url], "請求成功");
    }
}
