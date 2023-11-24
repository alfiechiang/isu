<?php

namespace App\Coupon\Type;

use App\Coupon\CouponEnums;
use App\Helpers\Utils;
use App\Models\Coupon;
use App\Models\CouponCode;
use App\Models\CouponCustomer;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class ACoupon
{
    public Customer $customer;
    public Coupon $couponModel;
    protected string $prefixType = '';
    protected array $couponSenders = [];

    public function __construct($couponModel, $customer)
    {
        $this->couponModel = $couponModel;
        $this->customer = $customer;
    }

    /**
     * @throws \Exception
     */
    public function issueCoupon($memo = '', $reference = null): CouponCustomer
    {
        if (!$this->isAvailable()) {
            throw new \Exception('Coupon is not available');
        }

        $couponCode = $this->generateCouponCode();
        $couponCustomer = $this->claimCoupon($couponCode, $memo, $reference);

        $this->notifySenders();

        return $couponCustomer;
    }

    public function attachSender($sender): void
    {
        $this->couponSenders[] = $sender;
    }

    public function claimCoupon(CouponCode $couponCode, $memo = '', $reference = null): CouponCustomer
    {
        $expired_at = null;
        $validity = $this->couponModel->validity;

        if ($validity && $validity != -1) {
            $expired_at = Carbon::now()->addMinutes($validity);
        }

        $couponCustomer = new CouponCustomer([
            'expired_at' => $expired_at,
            'memo' => $memo,
            'customer_id' => $this->customer->id,
            'coupon_code_id' => $couponCode->id,
            'coupon_id' => $this->couponModel->id,
        ]);

        $authUser = Auth::guard(Utils::getGuardName())->user();

        if($authUser){
            $couponCustomer->operator()->associate($authUser);
        }

        if($reference) {
            $couponCustomer->reference()->associate($reference);
        }

        $couponCustomer->save();

        return $couponCustomer;
    }

    public function generateCouponCode(): CouponCode
    {
        $uuid = Uuid::uuid4();
        $code = $this->generateNumber($uuid);

        return CouponCode::create([
            'uuid' => $uuid,
            'code' => $code,
            'coupon_id' => $this->couponModel->id,
        ]);
    }

    private function generateNumber(?UuidInterface $uuid = null): string
    {
        // 取得優惠券事件名所對應的 index
        if (($typePosition = array_search($this->prefixType, CouponEnums::mapTypes())) === false) {
            throw new InvalidArgumentException('Invalid coupon event.');
        }

        // 將 eventIndex 補零至 3 位數，並轉換成 A、B、C 等字元
        $index = str_pad($typePosition, 3, '0', STR_PAD_LEFT);
        $prefix = '';

        foreach (str_split($index) as $char) {
            $prefix .= chr(65 + $char);
        }

        // 取得當前日期，格式為 YYYYMMDD
        $suffix = date('Ymd');

        return "{$prefix}-{$uuid->toString()}-{$suffix}";
    }

    private function notifySenders(): void
    {
        foreach ($this->couponSenders as $sender) {
            $sender->created($this);
        }
    }
}
