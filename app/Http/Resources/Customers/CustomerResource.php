<?php

namespace App\Http\Resources\Customers;

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
            'name' => $this->whenNotNull($this->name),
            'email' => $this->whenNotNull($this->email),
            'phone' => $this->whenNotNull($this->phone),
            'citizenship' => $this->citizenship,
            'avatar' => $this->whenNotNull($this->avatar),
            'gender' => $this->whenNotNull($this->gender),
            'birthday' => $this->whenNotNull($this->birthday),
            'address' => $this->whenNotNull($this->address),
            'interest' => $this->whenNotNull($this->interest),
            'point_balance' => $this->point_balance,
            'stamps' => $this->stamps,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
