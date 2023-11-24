<?php

namespace App\Http\Resources\Customers;

use App\Models\Store;
use App\Traits\WhenMorphToLoaded;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponRedeemResource extends JsonResource
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
            'memo' => $this->memo,
            'created_at' => $this->created_at->toIso8601String(),
            'reference' => $this->whenMorphToLoaded('reference', [
                Store::class => StoreResource::class,
            ]),
        ];
    }
}
