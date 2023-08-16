<?php

namespace App\Http\Controllers\Customers;

use App\Coupon\CouponService;
use App\Exceptions\ErrException;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Response;
use App\Models\CouponCustomer;
use App\Models\Customer;
use App\Models\CustomerColumnChangeLog;
use App\Services\CustomerRole\AuthService;
use App\Services\CustomerRole\CustomerService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class ProfileController extends Controller
{
    /**
     * The AuthService instance.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    protected CustomerService $customerService;

    protected CouponService $couponService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @param CustomerService $customerService
     * @param CouponService $couponService
     */
    public function __construct(AuthService $authService, CustomerService $customerService, CouponService $couponService)
    {
        $this->authService = $authService;
        $this->customerService = $customerService;
        $this->couponService = $couponService;
    }

    public function index()
    {
        $authUser = $this->authService->user();
        return Response::format(200, $authUser, '請求成功');
    }

    public function store(StoreCustomerRequest $request)
    {
        $validator = $request->getValidatorInstance();

        try {
            if ($validator->fails()) {
                throw new ErrException($validator->errors()->first());
            }

            $data = $request->all();
            $authUser = $this->authService->user();
            // 如果電子郵件已經被註冊，則拋出異常.
            $email = Arr::get($data, 'email');
            if ($email && $email != $authUser->email && Customer::whereEmail($email)->exists()) {
                throw new ErrException('電子信箱已經被註冊');
            }

            // 如果手機號碼已經被註冊，則拋出異常.
            $phone = Arr::get($data, 'phone');
            if ($phone && $phone != $authUser->phone && Customer::wherePhone($phone)->exists()) {
                throw new ErrException('手機號碼已經被註冊');
            }

            DB::transaction(function () use ($data, $authUser) {

                if (isset($data['birthday'])) {
                    $customer = Customer::find($authUser->id);
                    if ($customer->birthday !== $data['birthday']) {
                        $exist = CustomerColumnChangeLog::where('customer_id', $customer->id)->where('table_name', 'customers')
                            ->where('column_name', 'birthday')->get();
                        if ($exist->isNotEmpty()) {
                            throw new ErrException('生日欄位只能更改一次');
                        }
                        CustomerColumnChangeLog::create([
                            'customer_id' => $customer->id,
                            'table_name' => 'customers',
                            'column_name' => 'birthday'
                        ]);
                    }
                }

                $authUser->fill($data);
                if ($authUser->isDirty()) {
                    $authUser->update();
                }

                $keysArray = ['name', 'birthday', 'county', 'phone', 'interest', 'postal', 'district', 'email', 'country', 'address', 'gender'];
                $persent=true;
                foreach ($authUser->getAttributes() as $key => $value) {
                    foreach($keysArray as $k){
                        if($k ==$key && empty($value)){
                            $persent=false;
                            break;
                        }
                    }
                }

                $exist=CouponCustomer::where('customer_id',$authUser->id)->where('coupon_id',config('coupon.customer.coupon_id'))->get();
                if($exist->isNotEmpty()){
                    $persent=false;
                }

                if($persent){
                    $insertData=[];
                    $created_at=date('Y-m-d H:i:s');
                    $expire_at = date('Y-m-d', strtotime("+1 year", strtotime($created_at)));
                    for($i=0 ;$i<10;$i++){
                        $data=[
                            'id'=>Str::uuid(),
                            'code_script'=>'M'.date('Ymd'),
                            'created_at'=>$created_at,
                            'expired_at'=>$expire_at ,
                            'status'=>1,
                            'coupon_cn_name'=>'會員大禮包',
                            'customer_id'=>$authUser->id,
                            'coupon_id'=>config('coupon.customer.coupon_id')
                        ];
                        $insertData[]=$data;
                    }
                    DB::table('coupon_customers')->insert($insertData);
                }


                if(isset($data['birthday'])){
                    $created_at =$authUser->created_at;
                    $expire_at = date('Y-m-d H:i:s', strtotime("+1 month", strtotime($authUser->birthday)));
                    if($created_at< $expire_at){
                        $year_start_date=now()->startOfYear()->format('Y-m-d');
                        $year_end_date = now()->endOfYear()->format('Y-m-d');
                        $exist = CouponCustomer::where('customer_id', $authUser->id)->whereBetween('created_at', [$year_start_date, $year_end_date])
                            ->where('coupon_id', config('coupon.birthday.coupon_id'))->get();
                        if($exist->isEmpty()){
                            CouponCustomer::create([
                                'id'=>Str::uuid(),
                                'code_script'=>'B'.date('Ymd'),
                                'created_at'=>date('Y-m-d H:i:s'),
                                'expired_at'=>$expire_at ,
                                'status'=>1,
                                'coupon_cn_name'=>'生日大禮',
                                'customer_id'=>$authUser->id,
                                'coupon_id'=>config('coupon.birthday.coupon_id')
                            ]);

                        }

                    }

                }
            


            });
            // 返回成功響應
            return Response::success();
        } catch (\Exception $e) {
            // 返回失敗響應
            return Response::errorFormat($e);
        }
    }
}
