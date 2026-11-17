<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as Auditing;
use OwenIt\Auditing\Contracts\Auditable;

class Ally extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'allies';

    protected $fillable = [
        'entity_id',
        'country_id',
        'name',
        'description',
        'settings',
    ];

    protected $auditInclude = [
        'entity_id',
        'country_id',
        'name',
        'description',
        'settings',
    ];

    protected $casts = [
        'settings' => 'object'
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function campaigns(): HasMany
    {
        return $this->hasMany(Campaign::class, 'ally_id');
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $query) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(
                    ['%', '_'],
                    ['\\%', '\\_'],
                    $term
                ) . '%';

                $query->where(function (Builder $query) use ($like) {
                    $query->where('name', 'ILIKE', $like)
                        ->orWhereHas('country', function (Builder $country) use ($like) {
                            $country->whereRaw(
                                "translations->>'es' ILIKE ?",
                                [$like]
                            );
                        });
                });
            }
        });
    }
}
