<?php

namespace App\Http\Resources\Auth\AuthInternal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AuthInternalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'auth' => [
                'user_id' => $this->userId()->value(),
                'employee_id' => $this->employeeId()->value(),
                'document_number' => $this->documentNumber()->value(),
                'email' => $this->email()->value(),
                'password' => Str::mask($this->password()->value(), '*', 5),
                'token' => $this->token()->value(),
                'roles' => $this->roles()->value(),
                'permissions' => $this->permissions()->value()
            ],
        ];
    }
}
