<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Vacant extends Model implements Auditable
{
    use HasFactory, Auditing;

    public const REVIEW = 'En revisión';
    public const APPROVED = 'Aprobado';
    public const REJECTED = 'Rechazado';
    public const PUBLISHED = 'Publicado';
    public const FINISHED = 'Finalizado';

    protected $table = 'vacants';

    protected $fillable = [
        'hiring_reason_id',
        'position_id',
        'person_id',
        'headquarter_id',
        'contract_type_id',
        'work_arrangement_id',
        'work_day_id',
        'salary_min',
        'salary_max',
        'start_date',
        'end_date',
        'description',
        'quantity',
        'status',
        'settings'
    ];

    protected $auditInclude = [
        'hiring_reason_id',
        'position_id',
        'person_id',
        'headquarter_id',
        'contract_type_id',
        'work_arrangement_id',
        'work_day_id',
        'salary_min',
        'salary_max',
        'start_date',
        'end_date',
        'description',
        'quantity',
        'status',
        'settings'
    ];

    protected $casts = [
        'salary_min' => 'float',
        'salary_max' => 'float',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'settings' => 'object'
    ];

    protected static function booted()
    {
        static::creating(function ($vacant) {
            $vacant->uuid ??= (string) Str::uuid();
        });
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function hiring_reason(): BelongsTo
    {
        return $this->belongsTo(HiringReason::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function contract_type(): BelongsTo
    {
        return $this->belongsTo(ContractType::class);
    }

    public function work_arrangement(): BelongsTo
    {
        return $this->belongsTo(WorkArrangement::class);
    }

    public function work_day(): BelongsTo
    {
        return $this->belongsTo(WorkDay::class);
    }

    public function benefits(): MorphToMany
    {
        return $this->morphToMany(Benefit::class, 'model', 'model_has_subitems', 'model_id', 'subitem_id');
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

                $q->whereHas('hiring_reason', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('position', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('headquarter', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('contract_type', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('work_arrangement', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('work_day', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('benefits', fn($sq) => $sq->where('name', 'ILIKE', $like))
                    ->orWhereHas('headquarter.entity', fn($sq) => $sq->where('name', 'ILIKE', $like));
            }
        });
    }

    public function scopeFilters($query, $request)
    {
        return $query->when($request->filled('experience'), function ($q) use ($request) {
            $experiences = $request->input('experience', []);

            $includesNoExperience = in_array('Sin experiencia', $experiences);
            $otherExperiences = collect($experiences)
                ->filter(fn($exp) => $exp !== 'Sin experiencia')
                ->values();

            $q->where(function ($main) use ($includesNoExperience, $otherExperiences) {
                if ($includesNoExperience) {
                    $main->where(function ($sub) {
                        $sub->whereHas('position.required_experiences', function ($w) {
                            $w->where('duration_months', 0);
                        })->orWhereDoesntHave('position.required_experiences');
                    });
                }

                if ($otherExperiences->isNotEmpty()) {
                    $main->orWhereHas('position.required_experiences', function ($sub) use ($otherExperiences) {
                        $sub->where(function ($sq) use ($otherExperiences) {
                            $otherExperiences->each(function ($exp) use ($sq) {
                                $sq->when($exp === '1-3 años', fn($w) => $w->orWhereBetween('duration_months', [1, 36]))
                                    ->when($exp === '4-7 años', fn($w) => $w->orWhereBetween('duration_months', [37, 84]))
                                    ->when($exp === '8-10 años', fn($w) => $w->orWhereBetween('duration_months', [85, 120]))
                                    ->when($exp === 'Más de 10 años', fn($w) => $w->orWhere('duration_months', '>', 120));
                            });
                        });
                    });
                }
            });
        })
            ->when($request->filled('work_arrangement_id'), function ($q) use ($request) {
                $work_arrangements = $request->input('work_arrangement_id', []);
                $q->whereHas('work_arrangement', fn($sq) => $sq->whereIn('name', $work_arrangements));
            })
            ->when($request->filled('enterprise_id'), function ($q) use ($request) {
                $empresas = $request->input('enterprise_id', []);
                $q->whereHas('headquarter.entity.enterprise', fn($sq) => $sq->whereIn('name', $empresas));
            })
            ->when($request->filled('contract_type_id'), function ($q) use ($request) {
                $contract_types = $request->input('contract_type_id', []);
                $q->whereHas('contract_type', fn($sq) => $sq->whereIn('name', $contract_types));
            })
            ->when($request->filled('salary'), function ($q) use ($request) {
                $ranges = $request->input('salary', []);

                $q->where(function ($sq) use ($ranges) {
                    collect($ranges)->each(function ($rango) use ($sq) {
                        $sq
                            ->when($rango === 'Menos de 1.000.000', function ($w) {
                                $w->orWhere(function ($sub) {
                                    $sub->where('salary_min', '<', 1000000);
                                });
                            })
                            ->when($rango === '1.000.000 - 2.000.000', function ($w) {
                                $w->orWhere(function ($sub) {
                                    $sub->where('salary_max', '>=', 1000000)
                                        ->where('salary_min', '<=', 1999999);
                                });
                            })
                            ->when($rango === '2.000.000 - 5.000.000', function ($w) {
                                $w->orWhere(function ($sub) {
                                    $sub->where('salary_max', '>=', 2000000)
                                        ->where('salary_min', '<=', 4999999);
                                });
                            })
                            ->when($rango === '5.000.000 - 10.000.000', function ($w) {
                                $w->orWhere(function ($sub) {
                                    $sub->where('salary_max', '>=', 5000000)
                                        ->where('salary_min', '<=', 9999999);
                                });
                            })
                            ->when($rango === 'Más de 10.000.000', function ($w) {
                                $w->orWhere(function ($sub) {
                                    $sub->where('salary_max', '>', 10000000);
                                });
                            });
                    });
                });
            })
            ->when($request->filled('country_id') || $request->filled('departament_id') || $request->filled('city_id'), function ($q) use ($request) {
                $countries = $request->input('country_id', []);
                $departaments = $request->input('departament_id', []);
                $cities = $request->input('city_id', []);
                $q->whereHas('headquarter', function ($sq) use ($countries, $departaments, $cities) {
                    $sq->when(!empty($countries), fn($w) => $w->whereHas('city.departament.country', fn($s) => $s->whereIn('name', $countries)))
                        ->when(!empty($departaments), fn($w) => $w->whereHas('city.departament', fn($s) => $s->whereIn('name', $departaments)))
                        ->when(!empty($cities), fn($w) => $w->whereHas('city', fn($s) => $s->whereIn('name', $cities)));
                });
            });
    }

    public static function statuses(): array
    {
        return [
            self::REVIEW,
            self::APPROVED,
            self::REJECTED,
            self::PUBLISHED,
            self::FINISHED,
        ];
    }
}
