<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Http\Resources\Stores\StoreEmployeeResource;
use App\Models\Store;
use App\Models\StoreEmployee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class StoreEmployeeController extends Controller
{
    /**
     * 取得所有商店帳號資料.
     *
     * @group Stores
     * @subgroup 商店帳號管理
     *
     * @authenticated
     *
     * @return AnonymousResourceCollection
     *
     * @queryParam page int 資料頁數 Example: 1
     *
     * @apiResourceCollection App\Http\Resources\Stores\StoreEmployeeResource
     * @apiResourceModel App\Models\StoreEmployee paginate=10
     */
    public function index(): AnonymousResourceCollection
    {
        $defaultOrderColumn = 'created_at';
        $defaultOrderBy = 'desc';

        $models = StoreEmployee::orderBy($defaultOrderColumn, $defaultOrderBy);
        $models = $models->paginate(self::PAGER);

        return StoreEmployeeResource::collection($models);
    }

    /**
     * 新增商店帳號資料
     *
     * @group Stores
     * @subgroup 商店帳號管理
     *
     * @authenticated
     *
     * @param Request $request
     * @return StoreEmployeeResource|Response
     *
     * @bodyParam name string required 名稱
     * @bodyParam email string 信箱
     * @bodyParam username string required 商店登入帳號
     * @bodyParam password string required 商店登入密碼
     *
     * @apiResource App\Http\Resources\Stores\StoreEmployeeResource
     * @apiResourceModel App\Models\StoreEmployee
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The password field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="帳號已經存在" {
     *   "errors": "帳號已經存在.",
     *   "code": 106003
     * }
     */
    public function store(Request $request): StoreEmployeeResource|Response
    {
        $data = $request->only('name', 'email', 'username', 'password');

        try {
            $validator = Validator::make($data, [
                'name' => 'required',
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException(
                    $validator->errors()->first(),
                    StatusCode::INVALID_ARGUMENT->value
                );
            }

            $username = Arr::get($data, 'username');
            if (StoreEmployee::whereUsername($username)->exists()) {
                throw new Exception("帳號已經存在.", StatusCode::STORE_ACCOUNT_USERNAME_EXISTS->value);
            }

            $store = Store::first();

            $data['store_id'] = $store->id;

            $model = StoreEmployee::create($data);

            return new StoreEmployeeResource($model);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 取得商店帳號資料
     *
     * @group Stores
     * @subgroup 商店帳號管理
     *
     * @authenticated
     *
     * @param string $id
     * @return StoreEmployeeResource|Response
     *
     * @apiResource App\Http\Resources\Stores\StoreEmployeeResource
     * @apiResourceModel App\Models\StoreEmployee
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到資料.",
     *   "code": 1002
     * }
     */
    public function show(string $id): StoreEmployeeResource|Response
    {
        try {
            $model = StoreEmployee::find($id);

            if(!$model) {
                throw new Exception("找不到資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            return new StoreEmployeeResource($model);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 更新商店帳號資料
     *
     * @group Stores
     * @subgroup 商店帳號管理
     *
     * @authenticated
     *
     * @param Request $request
     * @param string $id
     * @return StoreEmployeeResource|Response
     *
     * @bodyParam name string required 名稱
     * @bodyParam email string 信箱
     * @bodyParam username string required 商店登入帳號
     * @bodyParam password string required 商店登入密碼
     *
     * @apiResource App\Http\Resources\Stores\StoreEmployeeResource
     * @apiResourceModel App\Models\StoreEmployee
     * @response status=400 scenario="輸入參數錯誤" {
     *   "errors": "The password field is required.",
     *   "code": 1001
     * }
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到資料.",
     *   "code": 1002
     * }
     * @response status=400 scenario="帳號已經存在" {
     *   "errors": "帳號已經存在.",
     *   "code": 106003
     * }
     */
    public function update(Request $request, string $id): StoreEmployeeResource|Response
    {
        $data = $request->only('name', 'email', 'username', 'password');

        try {
            $validator = Validator::make($data, [
                'name' => 'required',
                'username' => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                throw new InvalidArgumentException(
                    $validator->errors()->first(),
                    StatusCode::INVALID_ARGUMENT->value
                );
            }

            $model = StoreEmployee::find($id);

            if(!$model) {
                throw new Exception("找不到資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $username = Arr::get($data, 'username');
            if ($username && $username != $model->username && StoreEmployee::whereUsername($username)->exists()) {
                throw new Exception("帳號已經存在.", StatusCode::STORE_ACCOUNT_USERNAME_EXISTS->value);
            }

            $model->update($data);

            return new StoreEmployeeResource($model);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    /**
     * 刪除商店帳號資料
     *
     * @group Stores
     * @subgroup 商店帳號管理
     *
     * @authenticated
     *
     * @param string $id
     * @return Response
     *
     * @response scenario=success
     * @response status=400 scenario="找不到資料" {
     *   "errors": "找不到資料.",
     *   "code": 1002
     * }
     */
    public function destroy(string $id): Response
    {
        try {
            $model = StoreEmployee::find($id);

            if(!$model) {
                throw new Exception("找不到資料.", StatusCode::MODEL_NOT_EXIST->value);
            }

            $model->delete();

            return response(['success' => true]);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }
}
