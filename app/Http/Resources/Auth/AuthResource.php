<?php

namespace App\Http\Resources\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'auth' => [
                'user_id' => $this->userId()->value(),
                'person_id' => $this->personId()->value(),
                'document_number' => $this->documentNumber()->value(),
                'email' => $this->email()->value(),
                'password' => Str::mask($this->password()->value(), '*', 5),
                'token' => $this->token()->value()
            ],
        ];
    }
}
