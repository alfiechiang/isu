<?php

namespace App\Http\Controllers\Stores;

use App\Enums\StatusCode;
use App\Http\Response;
use App\Services\StoreRole\AuthService;
use App\Services\Stores\StoreEmployeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Services\Stores\StorePrivilegeMenuService;
use Exception;

class AuthController extends Controller
{
    
    protected AuthService $authService;

    protected StorePrivilegeMenuService $storePrivilegeMenuService;

    protected StoreEmployeeService  $storeEmployeeService;

    public function __construct(AuthService $authService,StorePrivilegeMenuService $storePrivilegeMenuService,StoreEmployeeService $storeEmployeeService)
    {
        $this->storePrivilegeMenuService=$storePrivilegeMenuService;
        $this->authService = $authService;
        $this->storeEmployeeService =$storeEmployeeService;
    }

    public function login(Request $request)
    {
        // 取得輸入數據
        $credentials = $request->only('identifier', 'password');

        try {
            // 驗證輸入數據
            $validator = Validator::make($credentials, [
                'identifier' => 'required',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return Response::errorMsg(StatusCode::INVALID_ARGUMENT->value);
            }

            $token = $this->authService->login($credentials);
            // 返回成功響應
            return Response::format(200,['access_token'=>$token],'請求成功');

        } catch (\Exception $e) {
            // 返回失敗響應
            return Response::errorFormat($e);
        }
    }

    public function logout()
    {
        $this->authService->logout();

        return response(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        try {
            // 呼叫 AuthService 的 refresh 方法進行令牌刷新
            $result = $this->authService->refresh();

            // 返回成功響應
            return response($result);
        } catch (\Exception $e) {
            // 返回失敗響應
            return response(['errors' => $e->getMessage(), 'code' => $e->getCode()], 400);
        }
    }

    public function privilegeMenuList(Request $request){
       $res= $this->storePrivilegeMenuService->list();
       return Response::format(200,$res,'請求成功');
    }

    public function forgetpassword(Request $request){
        try {
            $this->storeEmployeeService->resetPassword($request->all());
            return Response::format(200,[],'請求成功');
        } catch (\Exception $e) {
            // 返回失敗響應
            return Response::errorFormat($e);
        }
    }
}
