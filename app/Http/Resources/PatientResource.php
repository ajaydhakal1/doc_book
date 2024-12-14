<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'name' => $this->user->name,
            'email' => $this->user->email,
            'age' => $this->age,
            'phone' => $this->phone,
            'address' => $this->address,
            'gender' => $this->gender,
            'role' => $this->roleName ? $this->roleName : null,
        ];
    }
}
