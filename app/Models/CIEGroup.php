<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class CIEGroup extends Model implements Auditable
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

    protected $casts = [
        'settings' => 'object'
    ];

    protected static function booted()
    {
        static::addGlobalScope('item_id', function (Builder $builder) {
            $builder->where('item_id', 49);
        });

        static::creating(function ($model) {
            $model->item_id = 49;
        });

        static::updating(function ($model) {
            $model->item_id = 49;
        });
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function cie_chapter(): MorphToMany
    {
        return $this->morphToMany(CIEChapter::class, 'model', 'model_has_subitems', 'model_id', 'subitem_id');
    }

    public function cie_subgroups(): MorphToMany
    {
        return $this->morphedByMany(CIESubgroup::class, 'model', 'model_has_subitems', 'subitem_id', 'model_id');
    }
}
