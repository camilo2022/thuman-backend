<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class LanguageProficiencie extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'language_proficiencies';

    protected $fillable = [
        'profile_id',
        'language_id',
        'language_level_id'
    ];

    protected $auditInclude = [
        'profile_id',
        'language_id',
        'language_level_id'
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function language_level(): BelongsTo
    {
        return $this->belongsTo(LanguageLevel::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }
}
