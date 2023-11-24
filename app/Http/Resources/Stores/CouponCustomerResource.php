<?php

namespace App\Http\Resources\Stores;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponCustomerResource extends JsonResource
{
    use WhenMorphToLoaded;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->coupon->name . ($this->memo ? '_' . $this->memo : '');

        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toIso8601String(),
            'expired_at' => $this->expired_at ? $this->expired_at->toIso8601String() : null,
            'name' => $name,
            'code' => $this->coupon_code->code,
            'status' => $this->status,
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
            'operator' => $this->whenMorphToLoaded('operator', [
                StoreEmployee::class => StoreEmployeeResource::class,
                Customer::class => CustomerResource::class,
            ]),
            'redeem' =>  new CouponCustomerRedeemResource($this->whenLoaded('redeem')),
            'customer' =>  new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
