<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Vacation extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'vacations';

    protected $fillable = [
        'person_id',
        'vacation_type_id',
        'period',
        'days',
        'start_date',
        'end_date',
        'status'
    ];

    protected $auditInclude = [
        'person_id',
        'vacation_type_id',
        'period',
        'days',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function vacation_type(): BelongsTo
    {
        return $this->belongsTo(VacationType::class);
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
                ->orWhereHas('vacation_type', function (Builder $sq) use ($like) {
                    $sq->where('name', 'ILIKE', $like)
                        ->orWhere('description', 'ILIKE', $like);
                })
                ->orWhere('period', 'ILIKE', $like)
                ->orWhere('days', 'ILIKE', $like)
                ->orWhere('start_date', 'ILIKE', $like)
                ->orWhere('end_date', 'ILIKE', $like)
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
