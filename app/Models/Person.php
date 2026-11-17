<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Person extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'people';

    protected $fillable = [
        'first_name',
        'second_name',
        'first_last_name',
        'second_last_name',
        'birth_date',
        'birth_city_id',
        'document_type_id',
        'document_issue_city_id',
        'document_number',
        'document_issue_date',
        'city_id',
        'address',
        'neighborhood',
        'dialing_country_id',
        'phone_number',
        'phone_number_verified_at',
        'email',
        'email_verified_at',
        'sex_id',
        'gender_id',
        'blood_type_id',
        'marital_status_id'
    ];

    protected $auditInclude = [
        'first_name',
        'second_name',
        'first_last_name',
        'second_last_name',
        'birth_date',
        'birth_city_id',
        'document_type_id',
        'document_issue_city_id',
        'document_number',
        'document_issue_date',
        'city_id',
        'address',
        'neighborhood',
        'dialing_country_id',
        'phone_number',
        'phone_number_verified_at',
        'email',
        'email_verified_at',
        'sex_id',
        'gender_id',
        'blood_type_id',
        'marital_status_id'
    ];

    protected $casts = [
        'birth_date' => 'datetime:Y-m-d H:i:s',
        'document_issue_date' => 'datetime:Y-m-d',
        'phone_number_verified_at' => 'datetime:Y-m-d H:i:s',
        'email_verified_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function health_entity_affiliation(): HasOne
    {
        return $this->hasOne(HealthEntityAffiliation::class);
    }

    public function pension_fund_affiliation(): HasOne
    {
        return $this->hasOne(PensionFundAffiliation::class);
    }

    public function risk_manager_affiliation(): HasOne
    {
        return $this->hasOne(RiskManagerAffiliation::class);
    }

    public function bank_certificate(): HasOne
    {
        return $this->hasOne(BankCertificate::class);
    }

    public function compensation_fund_affiliation(): HasOne
    {
        return $this->hasOne(CompensationFundAffiliation::class);
    }

    public function fiscal_document_certificate(): HasOne
    {
        return $this->hasOne(FiscalDocumentCertificate::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function dialing_country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'dialing_country_id');
    }

    public function birth_city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'birth_city_id');
    }

    public function document_issue_city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'document_issue_city_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function document_type(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class)->whereHas('item', fn($query) => $query->where('id', 4));
    }

    public function sex(): BelongsTo
    {
        return $this->belongsTo(Sex::class)->whereHas('item', fn($query) => $query->where('id', 9));
    }

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class)->whereHas('item', fn($query) => $query->where('id', 10));
    }

    public function blood_type(): BelongsTo
    {
        return $this->belongsTo(BloodType::class)->whereHas('item', fn($query) => $query->where('id', 11));
    }

    public function marital_status(): BelongsTo
    {
        return $this->belongsTo(MaritalStatus::class)->whereHas('item', fn($query) => $query->where('id', 12));
    }

    public function photo(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 100)->where('file_subtype_id', 125);
    }

    public function file(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 100)->where('file_subtype_id', 102);
    }

    public function signature(): MorphOne
    {
        return $this->morphOne(File::class, 'model')->where('file_type_id', 100)->where('file_subtype_id', 130);
    }

    public function verifications()
    {
        return $this->morphMany(Verification::class, 'model');
    }

    public function vacants(): MorphToMany
    {
        return $this->morphedByMany(Vacant::class, 'model', 'model_has_people', 'person_id', 'model_id');
    }

    public function entities(): MorphToMany
    {
        return $this->morphedByMany(Entity::class, 'model', 'model_has_people', 'person_id', 'model_id' );
    }

    public function termination_review(): HasOne
    {
        return $this->hasOne(Termination::class)->where('status', Termination::REVIEW)->latestOfMany();
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
                    $sq->orWhere('first_name', 'ILIKE', $like)
                        ->orWhere('second_name', 'ILIKE', $like)
                        ->orWhere('first_last_name', 'ILIKE', $like)
                        ->orWhere('second_last_name', 'ILIKE', $like)
                        ->orWhere('document_number', 'ILIKE', $like)
                        ->orWhere('email', 'ILIKE', $like)
                        ->orWhere('phone_number', 'ILIKE', $like)
                        ->orWhereHas('document_type', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like)
                                ->orWhere('description', 'ILIKE', $like)
                                ->orWhereRaw("(settings->>'abreviature') ILIKE ?", [$like]);
                        })
                        ->orWhereHas('dialing_country', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        })
                        ->orWhereHas('city', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        })
                        ->orWhereHas('sex', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        })
                        ->orWhereHas('gender', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        })
                        ->orWhereHas('blood_type', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        })
                        ->orWhereHas('marital_status', function (Builder $r) use ($like) {
                            $r->where('name', 'ILIKE', $like);
                        });
                });
            }
        });
    }

    public function notifyPhoneNumber(): Verification
    {
        $verification = $this->verifications()->create([
            'code' => Str::upper(Str::random(6)),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        //$verification->subitems()->sync([356, 358]);

        return $verification->refresh();
    }

    public function notifyEmail(): Verification
    {
        $verification = $this->verifications()->create([
            'code' => Str::upper(Str::random(6)),
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        //$verification->subitems()->sync([356, 359]);

        return $verification->refresh();
    }
}
