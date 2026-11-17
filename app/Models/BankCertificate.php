<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class BankCertificate extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const PENDING = 'Pendiente';
    public const REFRESHED = 'Actualizar';
    public const APPROVED = 'Aprobado';

    protected $table = 'bank_certificates';

    protected $fillable = [
        'person_id',
        'country_id',
        'bank_id',
        'bank_account_type_id',
        'account_number',
        'observation',
        'status'
    ];

    protected $auditInclude = [
        'person_id',
        'country_id',
        'bank_id',
        'bank_account_type_id',
        'account_number',
        'observation',
        'status'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function bank(): BelongsTo
    {
        return $this->belongsTo(Bank::class);
    }

    public function bank_account_type(): BelongsTo
    {
        return $this->belongsTo(BankAccountType::class);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model');
    }

    public static function statuses(): array
    {
        return [
            self::PENDING,
            self::REFRESHED,
            self::APPROVED
        ];
    }
}
