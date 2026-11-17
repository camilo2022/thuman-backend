<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class TestTechnical extends Model implements Auditable
{
    use Auditing;

    public const PENDING = 'Pendiente';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    public const INPROGRESS = 'En curso';
    public const FINISHED = 'Finalizado';
    public const EXPIRED = 'Expirado';

    protected $table = 'test_technicals';

    protected $fillable = [
        'application_id',
        'start_date',
        'end_date',
        'form_id',
        'questions',
        'answers',
        'leader_observation',
        'leader_id',
        'leader_status',
        'start_at',
        'end_at',
        'indication',
        'status'
    ];

    protected $auditInclude = [
        'application_id',
        'start_date',
        'end_date',
        'form_id',
        'questions',
        'answers',
        'leader_observation',
        'leader_id',
        'leader_status',
        'start_at',
        'end_at',
        'indication',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'questions' => 'array',
        'answers' => 'array',
        'start_at' => 'datetime:Y-m-d H:i:s',
        'end_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'leader_id');
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
                ->orWhere('start_date', 'ILIKE', $like)
                ->orWhere('end_date', 'ILIKE', $like)
                ->orWhere('status', 'ILIKE', $like);
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::PENDING,
            self::INPROGRESS,
            self::FINISHED,
            self::EXPIRED
        ];
    }
}
