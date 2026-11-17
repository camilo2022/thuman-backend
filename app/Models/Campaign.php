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

class Campaign extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'name',
        'description',
        'ally_id'
    ];

    protected $auditInclude = [
        'name',
        'description',
        'ally_id'
    ];

    public function allie(): BelongsTo
    {
        return $this->belongsTo(Ally::class, 'ally_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
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
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $query->where('name', 'ILIKE', $like);
            }
        });
    }
}
