<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Absence extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'absences';

    protected $fillable = [
        'person_id',
        'absence_type_id',
        'start_date',
        'end_date',
        'reason',
        'compensation',
        'status'
    ];

    protected $auditInclude = [
        'person_id',
        'absence_type_id',
        'start_date',
        'end_date',
        'reason',
        'compensation',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d H:i:s',
        'end_date' => 'date:Y-m-d H:i:s'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->withTrashed();
    }

    public function absence_type(): BelongsTo
    {
        return $this->belongsTo(AbsenceType::class);
    }

    public function file(): MorphOne
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

                $q->whereHas('person', function (Builder $sq) use ($like) {
                    $sq->where('first_name', 'ILIKE', $like)
                        ->orWhere('second_name', 'ILIKE', $like)
                        ->orWhere('first_last_name', 'ILIKE', $like)
                        ->orWhere('second_last_name', 'ILIKE', $like)
                        ->orWhere('document_number', 'ILIKE', $like)
                        ->orWhere('email', 'ILIKE', $like)
                        ->orWhere('phone_number', 'ILIKE', $like);
                })
                ->orWhereHas('absence_type', function (Builder $sq) use ($like) {
                    $sq->where('name', 'ILIKE', $like)
                        ->orWhere('description', 'ILIKE', $like);
                })
                ->orWhere('start_date', 'ILIKE', $like)
                ->orWhere('end_date', 'ILIKE', $like)
                ->orWhere('reason', 'ILIKE', $like)
                ->orWhere('compensation', 'ILIKE', $like)
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
