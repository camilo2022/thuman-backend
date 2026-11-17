<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Termination extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const TERMINATE = 'Terminar';
    public const RENEW = 'Renovar';
    public const NOTRENEW = 'No renovar';

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'terminations';

    protected $fillable = [
        'person_id',
        'model_id',
        'model_type',
        'reason',
        'decision',
        'status',
        'date'
    ];

    protected $auditInclude = [
        'person_id',
        'model_id',
        'model_type',
        'reason',
        'decision',
        'status'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->withTrashed();
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
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
                ->orWhereHasMorph('model', TerminationType::class, function (Builder $sq) use ($like) {
                        $sq->where('name', 'ILIKE', $like)
                            ->orWhere('description', 'ILIKE', $like);
                    }
                )
                ->orWhereHasMorph('model', Reason::class, function (Builder $sq) use ($like) {
                        $sq->where('name', 'ILIKE', $like)
                            ->orWhere('description', 'ILIKE', $like)
                            ->orWhereHas('termination_type', function (Builder $q) use ($like) {
                                $q->where('name', 'ILIKE', $like)
                                    ->orWhere('description', 'ILIKE', $like);
                            });
                    }
                )
                ->orWhere('reason', 'ILIKE', $like)
                ->orWhere('status', 'ILIKE', $like);
            }
        });
    }

    public static function decisions(): array
    {
        return [
            self::TERMINATE,
            self::RENEW,
            self::NOTRENEW
        ];
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
