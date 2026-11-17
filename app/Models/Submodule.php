<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Submodule extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    protected $table = 'submodules';

    protected $fillable = [
        'model_id',
        'model_type',
        'role_id',
        'permission_id',
        'type',
        'name',
        'route',
        'icon'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'role_id',
        'permission_id',
        'type',
        'name',
        'route',
        'icon'
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function submodules(): MorphMany
    {
        return $this->morphMany(Submodule::class, 'model')->orderBy('id', 'asc');
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where(function (Builder $sq) use ($like) {
                    $sq->where('name', 'ILIKE', $like)
                        ->orWhere('route', 'ILIKE', $like)
                        ->orWhere('type', 'ILIKE', $like);
                });
            }
        });
    }
}
