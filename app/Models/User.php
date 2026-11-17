<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class User extends Authenticatable implements Auditable
{
    use HasFactory, Notifiable, HasApiTokens, Auditing, SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    protected $table = 'users';

    protected $fillable = [
        'person_id',
        'password'
    ];

    protected $auditInclude = [
        'person_id',
        'password'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function isEmployee(): bool
    {
        return (bool) $this->person?->employee;
    }
}
