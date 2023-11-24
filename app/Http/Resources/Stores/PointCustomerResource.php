<?php

namespace App\Http\Resources\Stores;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PointCustomerResource extends JsonResource
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
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'expired_at' => $this->expired_at ? $this->expired_at->toIso8601String() : null,
            'source' => $this->source,
            'memo' => $this->memo,
            'point_balance' => $this->point_balance,
            'value' => $this->value,
            'is_redeem' => $this->is_redeem,
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
            'operator' => $this->whenMorphToLoaded('operator', [
                StoreEmployee::class => StoreEmployeeResource::class,
                Customer::class => CustomerResource::class,
            ]),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
