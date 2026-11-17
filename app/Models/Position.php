<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;
use Spatie\Permission\Traits\HasRoles;

class Position extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes, HasRoles;

    protected $guard_name = 'api';

    protected $table = 'positions';

    protected $fillable = [
        'area_id',
        'name',
        'profile_category_id',
        'description',
        'settings'
    ];

    protected $auditInclude = [
        'area_id',
        'name',
        'profile_category_id',
        'description',
        'settings'
    ];

    protected $casts = [
        'settings' => 'object'
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function profile_category(): BelongsTo
    {
        return $this->belongsTo(ProfileCategory::class);
    }

    public function academic_requirements(): HasMany
    {
        return $this->hasMany(AcademicRequirement::class);
    }

    public function required_experiences(): HasMany
    {
        return $this->hasMany(RequiredExperience::class);
    }

    public function required_skills(): HasMany
    {
        return $this->hasMany(RequiredSkill::class);
    }

    public function responsabilities(): HasMany
    {
        return $this->hasMany(Responsability::class);
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where('description', 'ILIKE', $like)
                ->orWhereHas('area', fn($sq) => $sq->where('name', 'ILIKE', $like))
                ->orWhereHas('profile_category', fn($sq) => $sq->where('name', 'ILIKE', $like));
            }
        });
    }
}
