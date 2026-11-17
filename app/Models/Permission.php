<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission as SpatiePermission;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Permission extends SpatiePermission implements Auditable
{
    use Auditing;

    protected $table = 'permissions';

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
