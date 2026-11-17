<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class InterviewVideo extends Model implements Auditable
{
    use Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';

    protected $table = 'interview_videos';

    protected $fillable = [
        'application_id',
        'form_id',
        'questions',
        'answers',
        'reviewer_observation',
        'reviewer_id',
        'status'
    ];

    protected $auditInclude = [
        'application_id',
        'form_id',
        'questions',
        'answers',
        'reviewer_observation',
        'reviewer_id',
        'status'
    ];

    protected $casts = [
        'questions' => 'array',
        'answers' => 'array'
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(Person::class, 'reviewer_id');
    }

    public static function statuses(): array
    {
        return [
            self::APPROVED,
            self::REJECTED
        ];
    }
}
