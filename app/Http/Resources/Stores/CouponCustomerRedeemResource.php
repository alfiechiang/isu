<?php

namespace App\Http\Resources\Stores;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponCustomerRedeemResource extends JsonResource
{
    use WhenMorphToLoaded;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->toIso8601String(),
            'memo' => $this->memo,
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
            'operator' => $this->whenMorphToLoaded('operator', [
                StoreEmployee::class => StoreEmployeeResource::class,
                Customer::class => CustomerResource::class,
            ]),
        ];
    }
}
