<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Permission\Models\Role as SpatieRole;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Role extends SpatieRole implements Auditable
{
    use Auditing;

    protected $table = 'roles';

    protected $fillable = [
        'name',
        'guard_name',
        'title',
        'description',
    ];

    protected $auditInclude = [
        'name',
        'guard_name',
        'title',
        'description',
    ];

    public function setGuardNameAttribute($value)
    {
        $this->attributes['guard_name'] = $value ?: 'api';
    }

    public function submodules(): HasMany
    {
        return $this->hasMany(Submodule::class);
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where(function (Builder $sq) use ($like) {
                    $sq->orWhere('name', 'ILIKE', $like)
                        ->orWhere('description', 'ILIKE', $like);
                });
            }
        });
    }
}
