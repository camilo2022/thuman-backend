<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Survey extends Model implements Auditable
{
    use Auditing;

    protected $table = 'surveys';

    protected $fillable = [
        'form_id',
        'questions',
        'answers',
        'person_id'
    ];

    protected $auditInclude = [
        'form_id',
        'questions',
        'answers',
        'person_id'
    ];

    protected $casts = [
        'questions' => 'object',
        'answers' => 'object'
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }
}
