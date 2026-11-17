<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class InterviewPsychotechnical extends Model implements Auditable
{
    use Auditing;

    public const PENDING = 'Pendiente';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    public const SCHEDULED = 'Programado';
    public const RESCHEDULED = 'Reprogramado';
    public const WITHDRAWN = 'Desistió';
    public const COMPLETED = 'Realizado';

    protected $table = 'interview_psychotechnicals';

    protected $fillable = [
        'application_id',
        'date_time',
        'questions',
        'answers',
        'psychologist_observation',
        'psychologist_score',
        'psychologist_id',
        'psychologist_status',
        'indication',
        'status'
    ];

    protected $auditInclude = [
        'application_id',
        'date_time',
        'questions',
        'answers',
        'psychologist_observation',
        'psychologist_score',
        'psychologist_id',
        'psychologist_status',
        'indication',
        'status'
    ];

    protected $casts = [
        'date_time' => 'datetime:Y-m-d H:i:s',
        'questions' => 'array',
        'answers' => 'array'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function psychologist(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'psychologist_id');
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->whereHas('application', function (Builder $sq) use ($like) {
                    $sq->whereHas('person', function (Builder $sq) use ($like) {
                        $sq->where('first_name', 'ILIKE', $like)
                            ->orWhere('second_name', 'ILIKE', $like)
                            ->orWhere('first_last_name', 'ILIKE', $like)
                            ->orWhere('second_last_name', 'ILIKE', $like)
                            ->orWhere('document_number', 'ILIKE', $like);
                    })
                    ->orWhereHas('vacant', function (Builder $sq) use ($like) {
                        $sq->whereHas('position', function (Builder $sq) use ($like) {
                                $sq->where('name', 'ILIKE', $like)
                                ->orWhereHas('area', fn($r) => $r->where('name', 'ILIKE', $like));
                            });
                    });
                })
                ->orWhere('date_time', 'ILIKE', $like)
                ->orWhere('status', 'ILIKE', $like);
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::SCHEDULED,
            self::RESCHEDULED,
            self::WITHDRAWN,
            self::COMPLETED
        ];
    }
}
