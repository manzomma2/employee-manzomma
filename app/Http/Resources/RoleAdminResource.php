<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleAdminResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $nameParts = preg_split('/\s+/', trim((string) $this->name), 2);

        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
