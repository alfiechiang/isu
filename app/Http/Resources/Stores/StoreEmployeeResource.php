<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreEmployeeResource extends JsonResource
{
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
            'updated_at' => $this->updated_at->toIso8601String(),
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'store' =>  new StoreResource($this->whenLoaded('store')),
        ];
    }
}
