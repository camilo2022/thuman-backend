<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class BloodType extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    protected $table = 'subitems';

    protected $fillable = [
        'item_id',
        'name',
        'description',
        'settings'
    ];

    protected $auditInclude = [
        'item_id',
        'name',
        'description',
        'settings'
    ];

    protected static function booted()
    {
        static::addGlobalScope('item_id', function (Builder $builder) {
            $builder->where('item_id', 11);
        });

        static::creating(function ($model) {
            $model->item_id = 11;
        });

        static::updating(function ($model) {
            $model->item_id = 11;
        });
    }

    protected $casts = [
        'settings' => 'object'
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
