<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Region extends Model implements Auditable
{
    use Auditing;

    protected $table = 'regions';

    protected $fillable = [
        'name',
        'translations',
        'settings'
    ];

    protected $auditInclude = [
        'name',
        'translations',
        'settings'
    ];

    protected $casts = [
        'translations' => 'json',
        'settings' => 'object'
    ];

    public function subregions(): HasMany
    {
        return $this->hasMany(Subregion::class);
    }
}
