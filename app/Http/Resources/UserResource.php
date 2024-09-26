<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'privilege'     => $this->getPermissions(),
        ];
    }

    private function getPermissions()
    {
        return $this->roles->map(function($role) {
            $permission = [
                "role" => $role->name,
                "permissions" => $role->permissions->pluck('name'),
            ];
            return $permission;
        });
    }
}
