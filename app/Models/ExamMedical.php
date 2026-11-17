<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class ExamMedical extends Model implements Auditable
{
    use Auditing;

    public const PENDING = 'Pendiente';
    public const APT = 'Apto';
    public const APT_WITH_RECOMMENDATIONS = 'Apto con recomendaciones';
    public const NOT_APT = 'No apto';
    public const POSTPONED = 'Aplazado';

    public const SCHEDULED = 'Programado';
    public const RESCHEDULED = 'Reprogramado';
    public const WITHDRAWN = 'Desistió';
    public const COMPLETED = 'Realizado';

    protected $table = 'exam_medicals';

    protected $fillable = [
        'application_id',
        'date_time',
        'city_id',
        'health_entity_id',
        'address',
        'neighborhood',
        'result',
        'epidemiological_program_id',
        'description',
        'indication',
        'status'
    ];

    protected $auditInclude = [
        'application_id',
        'date_time',
        'city_id',
        'health_entity_id',
        'address',
        'neighborhood',
        'result',
        'epidemiological_program_id',
        'description',
        'indication',
        'status'
    ];

    protected $casts = [
        'date_time' => 'datetime:Y-m-d H:i:s'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class,);
    }

    public function health_entity(): BelongsTo
    {
        return $this->belongsTo(HealthEntity::class);
    }

    public function epidemiological_program(): BelongsTo
    {
        return $this->belongsTo(EpidemiologicalProgram::class);
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
