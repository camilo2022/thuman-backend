<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Contract extends Model implements Auditable
{
    use Auditing, SoftDeletes;

    public const PENDING = 'Pendiente';
    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REFRESHED = 'Actualizar';
    public const REJECTED = 'Rechazado';
    public const WITHDRAWNED = 'Desistió';
    public const SIGNED = 'Firmado';

    protected $table = 'contracts';

    protected $fillable = [
        'application_id',
        'person_id',
        'headquarter_id',
        'contract_type_id',
        'position_id',
        'salary',
        'transport_allowance',
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
        'application_id',
        'person_id',
        'headquarter_id',
        'contract_type_id',
        'position_id',
        'salary',
        'transport_allowance',
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
        'transport_allowance' => 'float',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->withTrashed();
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

    public function pdf(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 97)->where('file_subtype_id', 131);
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

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->where(function (Builder $sq) use ($like) {
                    $sq->whereHas('person', function (Builder $person) use ($like) {
                        $person->where('first_name', 'ILIKE', $like)
                            ->orWhere('second_name', 'ILIKE', $like)
                            ->orWhere('first_last_name', 'ILIKE', $like)
                            ->orWhere('second_last_name', 'ILIKE', $like);
                    })
                        ->orWhereHas('contract_type', function (Builder $contractType) use ($like) {
                            $contractType->where('name', 'ILIKE', $like);
                        });
                });
            }
        });
    }
}
