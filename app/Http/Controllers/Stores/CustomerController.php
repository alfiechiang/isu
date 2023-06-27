<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Http\Resources\Stores\{CustomerResource};
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    /**
     * 取得所有顧客資料.
     *
     * @group Stores
     * @subgroup 顧客管理
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     * @queryParam query string 搜尋欄位 name、email、phone Example: user@example.com
     *
     * @apiResourceCollection App\Http\Resources\Stores\CustomerResource
     * @apiResourceModel App\Models\Customer paginate=10
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $query = Customer::query();

        $search = trim($request->get('query'));
        if ($search != '') {
            $query->where(function ($query) use ($search) {
                $query->orWhere('name', 'like', '%' . $search . '%');
                $query->orWhere('email', 'like', '%' . $search . '%');
                $query->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($defaultOrderColumn, $defaultOrderBy);

        $models = $query->paginate(self::PAGER);

        return CustomerResource::collection($models);
    }

    /**
     * 取得顧客資料
     *
     * @group Stores
     * @subgroup 顧客管理
     *
     * @authenticated
     *
     * @param string $id
     * @return CustomerResource|Response
     *
     * @apiResource App\Http\Resources\Stores\CustomerResource
     * @apiResourceModel App\Models\Customer
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到資料.",
     *   "code": 1002
     * }
     */
    public function show(string $id): CustomerResource|Response
    {
        try {
            $model = Customer::find($id);

            if(!$model) {
                throw new Exception("找不到資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            return new CustomerResource($model);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 刪除顧客資料
     *
     * @group Stores
     * @subgroup 顧客管理
     *
     * @authenticated
     *
     * @param string $id
     * @return \Illuminate\Http\Response
     *
     * @response scenario=success
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到資料.",
     *   "code": 1002
     * }
     */
    public function destroy(string $id): \Illuminate\Http\Response
    {
        try {
            $model = Customer::find($id);

            if(!$model) {
                throw new Exception("找不到資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $model->delete();

             return response(['success' => true]);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
