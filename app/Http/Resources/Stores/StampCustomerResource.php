<?php

namespace App\Http\Resources\Stores;

use App\Models\Customer;
use App\Models\Store;
use App\Models\StoreEmployee;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StampCustomerResource extends JsonResource
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
            'created_at' => $this->created_at ?$this->created_at->toIso8601String(): null,
            'expired_at' => $this->expired_at ?$this->expired_at->toIso8601String(): null,
            'consumed_at' => $this->consumed_at ?$this->expired_at->format("Y-m-d"): null,
            'memo' => $this->memo,
            'type' => $this->type,
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
            'operator' => $this->whenMorphToLoaded('operator', [
                StoreEmployee::class => StoreEmployeeResource::class,
                Customer::class => CustomerResource::class,
            ]),
            'remain_quantity' => $this->remain_quantity,
            'value' => $this->value,
            'is_redeem' => $this->is_redeem,
            'store' =>  new StoreResource($this->whenLoaded('store')),
            'customer' => new CustomerResource($this->whenLoaded('customer')),
        ];
    }
}
