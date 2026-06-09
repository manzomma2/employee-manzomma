<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role_id' => $this->role_id,
            'sector_id' => $this->sector_id,
            'role' => new RoleAdminResource($this->whenLoaded('role')),
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
