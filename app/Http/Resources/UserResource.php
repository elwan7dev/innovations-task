<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'email' => $this->email ?? null,
            'phone' => $this->phone ?? null,
            'address' => $this->address ?? null,
            'block' => (bool)$this->block,
            'media' => $this->getMedia(),
            'role' => $this->roles->first() ? $this->roles->first()->name : null
        ];
    }
}
