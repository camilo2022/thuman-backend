<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Induction extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'inductions';

    protected $fillable = [
        'name',
        'description',
        'assignable_id',
        'assignable_type',
        'model_id',
        'model_type',
    ];

    protected $auditInclude = [
        'name',
        'description',
        'assignable_id',
        'assignable_type',
        'model_id',
        'model_type',
    ];

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
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
                        ->orWhere('description', 'ILIKE', $like)
                        ->orWhereHasMorph(
                            'assignable',
                            [Enterprise::class, Entity::class, Headquarter::class],
                            function (Builder $r) use ($like) {
                                $r->where('name', 'ILIKE', $like);
                            }
                        )
                        ->orWhereHasMorph(
                            'model',
                            [Area::class, Position::class],
                            function (Builder $r) use ($like) {
                                $r->where('name', 'ILIKE', $like);
                            }
                        );
                });
            }
        });
    }
}
