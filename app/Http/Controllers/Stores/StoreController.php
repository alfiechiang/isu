<?php

namespace App\Http\Controllers\Stores;

use App\Http\Resources\Stores\StoreResource;
use App\Models\Store;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StoreController extends Controller
{
    /**
     * 取得所有商店資料.
     *
     * @group Stores
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Stores\StoreResource
     * @apiResourceModel App\Models\Store paginate=10
     */
    public function index(): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $models = Store::orderBy($defaultOrderColumn, $defaultOrderBy);
        $models = $models->paginate(self::PAGER);

        return StoreResource::collection($models);
    }
}
