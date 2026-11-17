<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Enterprise extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'enterprises';

    protected $fillable = [
        'name',
        'description'
    ];

    protected $auditInclude = [
        'name',
        'description'
    ];

    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
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
