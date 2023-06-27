<?php

namespace App\Http\Resources\Customers;

use App\Models\Store;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'expired_at' => $this->expired_at ? $this->expired_at->toIso8601String() : null,
            'name' => $name,
            'code' => $this->coupon_code->code,
            'created_at' => $this->created_at->toIso8601String(),
            'status' => $this->status,
            'redeem' =>  new CouponRedeemResource($this->whenLoaded('redeem')),
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
        ];
    }
}
