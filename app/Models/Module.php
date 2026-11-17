<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Module extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    public const INTERNAL = 'Interno';
    public const EXTERNAL = 'Externo';

    protected $table = 'modules';

    protected $fillable = [
        'name',
        'type',
        'route',
    ];

    protected $auditInclude = [
        'name',
        'type',
        'route',
    ];

    public function submodules(): MorphMany
    {
        return $this->morphMany(Submodule::class, 'model');
    }

    public static function types(): array
    {
        return [
            self::INTERNAL,
            self::EXTERNAL
        ];
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
                        ->orWhere('type', 'ILIKE', $like)
                        ->orWhere('route', 'ILIKE', $like);
                });
            }
        });
    }
}
