<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Policy extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    public const REVIEW = 'En revisión';
    public const ACTIVED = 'Vigente';
    public const EXPIRED = 'Vencida';
    public const CANCELED = 'Cancelada';

    protected $table = 'policies';

    protected $fillable = [
        'model_id',
        'model_type',
        'risk_manager_id',
        'policy_type_id',
        'code',
        'value',
        'insured_value',
        'start_date',
        'end_date',
        'status',
        'settings'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'risk_manager_id',
        'policy_type_id',
        'code',
        'value',
        'insured_value',
        'start_date',
        'end_date',
        'status',
        'settings'
    ];

    protected $casts = [
        'value' => 'float',
        'insured_value' => 'float',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'settings' => 'object'
    ];

    protected static function booted()
    {
        static::creating(function ($policy) {
            $policy->uuid ??= (string) Str::uuid();
        });
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function risk_manager(): BelongsTo
    {
        return $this->belongsTo(RiskManager::class);
    }

    public function policy_type(): BelongsTo
    {
        return $this->belongsTo(PolicyType::class);
    }
}
