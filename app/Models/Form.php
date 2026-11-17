<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Form extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    protected $table = 'forms';

    protected $fillable = [
        'form_type_id',
        'assignable_id',
        'assignable_type',
        'model_id',
        'model_type',
        'questions'
    ];

    protected $auditInclude = [
        'form_type_id',
        'assignable_id',
        'assignable_type',
        'model_id',
        'model_type',
        'questions'
    ];

    protected $casts = [
        'questions' => 'object'
    ];

    public function form_type(): BelongsTo
    {
        return $this->belongsTo(FormType::class);
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

        if ($search === '') {
            return $query;
        }

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where(function (Builder $sub) use ($like) {
                    $sub->whereHas('form_type', function (Builder $r) use ($like) {
                        $r->where('name', 'ILIKE', $like)
                            ->orWhere('description', 'ILIKE', $like);
                    })
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
