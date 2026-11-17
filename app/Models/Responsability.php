<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Responsability extends Model implements Auditable
{
    use HasFactory, Auditing;

    protected $table = 'responsabilities';

    protected $fillable = [
        'position_id',
        'name',
        'description'
    ];

    protected $auditInclude = [
        'position_id',
        'name',
        'description'
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
