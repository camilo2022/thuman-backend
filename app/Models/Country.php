<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Country extends Model implements Auditable
{
    use Auditing;

    protected $table = 'countries';

    protected $fillable = [
        'subregion_id',
        'name',
        'iso3',
        'iso2',
        'numeric_code',
        'phone_code',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'nationality',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'translations',
        'settings'
    ];

    protected $auditInclude = [
        'subregion_id',
        'name',
        'iso3',
        'iso2',
        'numeric_code',
        'phone_code',
        'currency',
        'currency_name',
        'currency_symbol',
        'tld',
        'native',
        'nationality',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'translations',
        'settings'
    ];

    protected $casts = [
        'translations' => 'json',
        'settings' => 'object'
    ];

    public function departaments(): HasMany
    {
        return $this->hasMany(Departament::class);
    }

    public function allies(): HasMany
    {
        return $this->hasMany(Ally::class);
    }

    public function subregion(): BelongsTo
    {
        return $this->belongsTo(Subregion::class);
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
                    $sq->orWhere('name', 'ILIKE', $like)
                        ->orWhere('emoji', 'ILIKE', $like)
                        ->orWhere('phone_code', 'ILIKE', $like)
                        ->orWhere('currency', 'ILIKE', $like)
                        ->orWhere('currency_name', 'ILIKE', $like)
                        ->orWhere('currency_symbol', 'ILIKE', $like)
                        ->orWhere('tld', 'ILIKE', $like)
                        ->orWhere('native', 'ILIKE', $like)
                        ->orWhere('nationality', 'ILIKE', $like);
                });
            }
        });
    }
}
