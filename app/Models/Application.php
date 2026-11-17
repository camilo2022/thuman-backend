<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Application extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const PENDING = 'Pendiente';
    public const REVIEW = 'En revisión';
    public const TEST = 'Prueba';
    public const FINALIST = 'Finalista';
    public const HIRING = 'Contratación';
    public const REJECTED = 'Rechazado';
    public const WITHDRAWN = 'Desistió';
    public const LINKED = 'Vinculado';

    protected $table = 'applications';

    protected $fillable = [
        'vacant_id',
        'person_id',
        'form_id',
        'questions',
        'answers',
        'salary',
        'observation',
        'status'
    ];

    protected $auditInclude = [
        'vacant_id',
        'person_id',
        'form_id',
        'questions',
        'answers',
        'salary',
        'observation',
        'status'
    ];

    protected $casts = [
        'questions' => 'array',
        'answers' => 'array',
        'salary' => 'float'
    ];

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class)->withTrashed();
    }

    public function interview_video(): HasOne
    {
        return $this->hasOne(InterviewVideo::class);
    }

    public function criminal_record(): HasOne
    {
        return $this->hasOne(CriminalRecord::class);
    }

    public function interview_initial(): HasOne
    {
        return $this->hasOne(InterviewInitial::class);
    }

    public function interview_psychotechnical(): HasOne
    {
        return $this->hasOne(InterviewPsychotechnical::class);
    }

    public function test_technical(): HasOne
    {
        return $this->hasOne(TestTechnical::class);
    }

    public function test_psychotechnical(): HasOne
    {
        return $this->hasOne(TestPsychotechnical::class);
    }

    public function exam_medical(): HasOne
    {
        return $this->hasOne(ExamMedical::class);
    }

    public function exam_polygraph(): HasOne
    {
        return $this->hasOne(ExamPolygraph::class);
    }

    public function vacant(): BelongsTo
    {
        return $this->belongsTo(Vacant::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->withTrashed();
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function video_interview(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }

    public function verifications()
    {
        return $this->morphMany(Verification::class, 'model');
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
                ->orWhereHas('vacant', function (Builder $sq) use ($like) {
                    $sq->where('description', 'ILIKE', $like)
                        ->orWhereHas('hiring_reason', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('position', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('headquarter', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('contract_type', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('work_arrangement', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('work_day', fn($r) => $r->where('name', 'ILIKE', $like))
                        ->orWhereHas('benefits', fn($r) => $r->where('name', 'ILIKE', $like));
                });
            }
        });
    }

    public static function statuses(): array
    {
        return [
            self::PENDING,
            self::REVIEW,
            self::TEST,
            self::FINALIST,
            self::HIRING,
            self::REJECTED,
            self::WITHDRAWN,
            self::LINKED
        ];
    }

    public function notifyToken(): Verification
    {
        $verification = $this->verifications()->create([
            'code' => Str::upper(Str::random(10)),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        //$verification->subitems()->sync([356, 358]);

        return $verification->refresh();
    }
}
