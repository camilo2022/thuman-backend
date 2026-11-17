<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Subregion extends Model implements Auditable
{
    use Auditing;

    protected $table = 'subregions';

    protected $fillable = [
        'region_id',
        'name',
        'translations',
        'settings'
    ];

    protected $auditInclude = [
        'region_id',
        'name',
        'translations',
        'settings'
    ];

    protected $casts = [
        'translations' => 'json',
        'settings' => 'object'
    ];

    public function countries(): HasMany
    {
        return $this->hasMany(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }
}
