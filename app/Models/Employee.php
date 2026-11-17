<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as Auditing;

class Employee extends Model implements Auditable
{
    use HasFactory, Auditing, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'person_id',
        'headquarter_id',
        'position_id',
        'start_date',
        'end_date',
        'settings'
    ];

    protected $auditInclude = [
        'person_id',
        'headquarter_id',
        'position_id',
        'start_date',
        'end_date',
        'settings'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'settings' => 'object'
    ];

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->withTrashed();
    }

    public function headquarter(): BelongsTo
    {
        return $this->belongsTo(Headquarter::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function scopeSearch(Builder $query, ?string $search = null): Builder
    {
        $search = trim((string) $search);
        if ($search === '') return $query;

        $terms = preg_split('/\s+/', $search, -1, PREG_SPLIT_NO_EMPTY);

        return $query->where(function (Builder $q) use ($terms) {
            foreach ($terms as $term) {
                $like = '%' . str_replace(['%', '_'], ['\\%', '\\_'], $term) . '%';

                $q->whereHas('person', function (Builder $sq) use ($like) {
                    $sq->where('first_name', 'ILIKE', $like)
                        ->orWhere('second_name', 'ILIKE', $like)
                        ->orWhere('first_last_name', 'ILIKE', $like)
                        ->orWhere('second_last_name', 'ILIKE', $like)
                        ->orWhere('document_number', 'ILIKE', $like)
                        ->orWhere('email', 'ILIKE', $like)
                        ->orWhere('phone_number', 'ILIKE', $like);
                })
                    ->orWhereHas('position', function (Builder $sq) use ($like) {
                        $sq->where('name', 'ILIKE', $like)
                            ->orWhere('description', 'ILIKE', $like)
                            ->orWhereHas('area', function (Builder $sq) use ($like) {
                                $sq->where('name', 'ILIKE', $like)
                                    ->orWhere('description', 'ILIKE', $like);
                            });
                    });
            }
        });
    }
}
