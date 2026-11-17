<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Resignation extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'resignations';

    protected $fillable = [
        'person_id',
        'resignation_type_id',
        'date',
        'reason',
        'status'
    ];

    protected $auditInclude = [
        'person_id',
        'resignation_type_id',
        'date',
        'reason',
        'status'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function resignation_type(): BelongsTo
    {
        return $this->belongsTo(ResignationType::class);
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->whereHas('person', function (Builder $sq) use ($like) {
                    $sq->where('first_name', 'ILIKE', $like)
                        ->orWhere('second_name', 'ILIKE', $like)
                        ->orWhere('first_last_name', 'ILIKE', $like)
                        ->orWhere('second_last_name', 'ILIKE', $like)
                        ->orWhere('document_number', 'ILIKE', $like)
                        ->orWhere('email', 'ILIKE', $like)
                        ->orWhere('phone_number', 'ILIKE', $like);
                })
                ->orWhereHas('resignation_type', function (Builder $sq) use ($like) {
                    $sq->where('name', 'ILIKE', $like)
                        ->orWhere('description', 'ILIKE', $like);
                })
                ->orWhere('date', 'ILIKE', $like)
                ->orWhere('reason', 'ILIKE', $like)
                ->orWhere('status', 'ILIKE', $like);
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::REVIEW,
            self::APPROVED,
            self::REJECTED
        ];
    }
}
