<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Addendum extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    public const PENDING = 'Pendiente';
    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REFRESHED = 'Actualizar';
    public const REJECTED = 'Rechazado';
    public const WITHDRAWNED = 'Desistió';
    public const SIGNED = 'Firmado';

    protected $table = 'addendums';

    protected $fillable = [
        'contract_id',
        'addendum_type_id',
        'headquarter_id',
        'contract_type_id',
        'position_id',
        'salary',
        'payment_period_id',
        'start_date',
        'end_date',
        'person_observation',
        'person_signed_at',
        'person_status',
        'legal_representative_signed_at',
        'legal_representative_status',
        'settings'
    ];

    protected $auditInclude = [
        'contract_id',
        'addendum_type_id',
        'headquarter_id',
        'contract_type_id',
        'position_id',
        'salary',
        'payment_period_id',
        'start_date',
        'end_date',
        'person_observation',
        'person_signed_at',
        'person_status',
        'legal_representative_signed_at',
        'legal_representative_status',
        'settings'
    ];

    protected $casts = [
        'salary' => 'float',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'person_signed_at' => 'date:Y-m-d H:i:s',
        'legal_representative_signed_at' => 'date:Y-m-d H:i:s',
        'settings' => 'object'
    ];

    protected static function booted()
    {
        static::creating(function ($contract) {
            $contract->uuid ??= (string) Str::uuid();
        });
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function addendum_type(): BelongsTo
    {
        return $this->belongsTo(AddendumType::class);
    }

    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function contract_type(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function payment_period(): BelongsTo
    {
        return $this->belongsTo(PaymentPeriod::class);
    }

    public function person_signature(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 100)->where('file_subtype_id', 130);
    }

    public function legal_representative_signature(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 97)->where('file_subtype_id', 130);
    }

    public function file_subtypes(): MorphToMany
    {
        return $this->morphedByMany(FileSubtype::class, 'model', 'model_has_subitems', 'subitem_id', 'model_id');
    }

    public function verifications()
    {
        return $this->morphMany(Verification::class, 'model');
    }

    public function notifyToken(): Verification
    {
        $verification = $this->verifications()->create([
            'code' => Str::upper(Str::random(10)),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        //$verification->subitems()->sync([356, 358]);

        return $verification->refresh();
    }
}
