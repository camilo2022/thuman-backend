<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class EmergencyContact extends Model implements Auditable
{
    use Auditing;

    protected $table = 'people';

    protected $fillable = [
        'profile_id',
        'first_name',
        'second_name',
        'first_last_name',
        'second_last_name',
        'relationship_id',
        'dialing_country_id',
        'phone_number'
    ];

    protected $auditInclude = [
        'profile_id',
        'first_name',
        'second_name',
        'first_last_name',
        'second_last_name',
        'relationship_id',
        'dialing_country_id',
        'phone_number'
    ];
    
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }

    public function relationship(): BelongsTo
    {
        return $this->belongsTo(Relationship::class);
    }

    public function dialing_country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'dialing_country_id');
    }
}
