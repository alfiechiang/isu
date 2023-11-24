<?php

namespace App\Http\Resources\Customers;

use App\Http\Resources\Stores\StoreResource;
use App\Models\Store;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StampResource extends JsonResource
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
            'created_at' => $this->created_at ?$this->created_at->toIso8601String(): null,
            'expired_at' => $this->expired_at ?$this->expired_at->toIso8601String(): null,
            'memo' => $this->memo,
            'type' => $this->type,
            'remain_quantity' => $this->remain_quantity,
            'value' => $this->value,
            'is_redeem' => $this->is_redeem,
            'store' =>  new StoreResource($this->whenLoaded('store')),
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
        ];
    }
}
