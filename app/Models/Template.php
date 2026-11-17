<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Template extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    protected $table = 'files';

    protected $fillable = [
        'model_id',
        'model_type',
        'file_type_id',
        'file_subtype_id',
        'name',
        'path',
        'mime',
        'extension',
        'size',
        'metadata',
        'settings'
    ];

    protected $auditInclude = [
        'model_id',
        'model_type',
        'file_type_id',
        'file_subtype_id',
        'name',
        'path',
        'mime',
        'extension',
        'size',
        'metadata',
        'settings'
    ];

    protected $casts = [
        'metadata' => 'object',
        'settings' => 'object'
    ];

    public function getPathAttribute($value): ?string
    {
        return $value ? url('storage/' . $value) : null;
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function file_type(): BelongsTo
    {
        return $this->belongsTo(Subitem::class, 'file_type_id');
    }

    public function file_subtype(): BelongsTo
    {
        return $this->belongsTo(Subitem::class, 'file_subtype_id');
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
                        ->orWhereHas('file_type', fn($sq) => $sq->where('name', 'ILIKE', $like))
                        ->orWhereHas('file_subtype', fn($sq) => $sq->where('name', 'ILIKE', $like));
                });
            }
        });
    }
}
