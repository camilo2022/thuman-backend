<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Auditable as Auditing;
use OwenIt\Auditing\Contracts\Auditable;

class OrganizationalStructure extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'organizational_structures';

    public const PENDING = 'Pendiente';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';
    public const INACTIVE = 'Inactivo';

    protected $fillable = [
        'entity_id',
        'position_id',
        'parent_position_id',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    protected $auditInclude = [
        'entity_id',
        'position_id',
        'parent_position_id',
        'start_date',
        'end_date',
        'status',
        'reason',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function parentPosition(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'parent_position_id');
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
                    $query->whereHas('entity', fn($entity) => $entity->where('name', 'ILIKE', $like))
                        ->orWhereHas('position', fn($position) => $position->where('name', 'ILIKE', $like))
                        ->orWhereHas('parentPosition', fn($position) => $position->where('name', 'ILIKE', $like))
                        ->orWhere('status', 'ILIKE', $like);
                });
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::APPROVED,
            self::REJECTED,
            self::INACTIVE
        ];
    }
}
