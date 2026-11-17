<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Verification extends Model implements Auditable
{
    use Auditing;

    protected $fillable = [
        'model_id',
        'model_type',
        'external_id',
        'code',
        'verified',
        'expires_at',
        'verified_at'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'external_id',
        'code',
        'verified',
        'expires_at',
        'verified_at'
    ];

    protected $casts = [
        'verified' => 'boolean',
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function model()
    {
        return $this->morphTo();
    }

    public function subitems(): MorphToMany
    {
        return $this->morphToMany(Subitem::class, 'model', 'model_has_subitems');
    }
    
    public function getNotificationTypeAttribute()
    {
        return $this->subitems()->whereHas('item', fn($query) => $query->where('id', 24))->first();
    }

    public function getNotificationChannelAttribute()
    {
        return $this->subitems()->whereHas('item', fn($query) => $query->where('id', 25))->first();
    }
    
    public function isExpired(): bool
    {
        return $this->expires_at?->isPast() ?? true;
    }

    public function isVerified(): bool
    {
        return $this->verified === true && !is_null($this->verified_at);
    }

    public function verifyCode(): self
    {
        $this->update([
            'verified' => true,
            'verified_at' => Carbon::now(),
        ]);

        return $this->refresh();
    }
    
    public function attachExternalId(string $externalId): self
    {
        $this->update([
            'external_id' => $externalId,
        ]);

        return $this->refresh();
    }
}
