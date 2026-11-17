<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Entity extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'entities';

    protected $fillable = [
        'enterprise_id',
        'entity_type_id',
        'name',
        'country_id',
        'document_type_id',
        'document_number',
        'person_id',
        'about_us',
        'mission',
        'vision',
        'settings'
    ];

    protected $auditInclude = [
        'enterprise_id',
        'entity_type_id',
        'name',
        'country_id',
        'document_type_id',
        'document_number',
        'person_id',
        'about_us',
        'mission',
        'vision',
        'settings'
    ];

    protected $casts = [
        'settings' => 'object'
    ];

    public function headquarters()
    {
        return $this->hasMany(Headquarter::class);
    }

    public function enterprise(): BelongsTo
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function entity_type(): BelongsTo
    {
        return $this->belongsTo(EntityType::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(File::class, 'model')->where('file_type_id', 97);
    }

    public function people()
    {
        return $this->morphToMany(Person::class, 'model', 'model_has_people', 'model_id', 'person_id');
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where(function (Builder $sq) use ($like) {
                    $sq->orWhere('name', 'ILIKE', $like);
                });
            }
        });
    }

    public function scopeFilters($query, $request)
    {
        return $query->when($request->filled('enterprise_id'), function ($q) use ($request) {
            $q->whereHas('enterprise', fn($sq) => $sq->whereIn('name',$request->input('enterprise_id', [])));
        })
        ->when($request->filled('country_id'), function ($q) use ($request) {
            $q->when(!empty($countries), fn($w) => $w->whereHas('country', fn($s) => $s->whereIn('name', $request->input('country_id', []))));
        });
    }
}
