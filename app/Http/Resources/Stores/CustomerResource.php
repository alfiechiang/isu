<?php

namespace App\Http\Resources\Stores;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'avatar' => $this->avatar_full_url,
            'status' => $this->status,
            'gender' => $this->gender,
            'birthday' => $this->birthday,
            'address' => $this->address,
            'interest' => $this->interest,
            'point_balance' => $this->point_balance,
            'stamps' => $this->stamps,
            'created_at' => $this->created_at ? $this->created_at->toIso8601String() : null,
            'updated_at' => $this->updated_at ? $this->updated_at->toIso8601String() : null,
        ];
    }
}
