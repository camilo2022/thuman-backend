<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Gender\Contracts\GenderContract;
use App\Models\Gender as GenderModel;
use App\Domain\Gender\Gender;
use App\Domain\Gender\Values\GenderCreatedAt;
use App\Domain\Gender\Values\GenderDeletedAt;
use App\Domain\Gender\Values\GenderId;
use App\Domain\Gender\Values\GenderName;
use App\Domain\Gender\Values\GenderDescription;
use App\Domain\Gender\Values\GenderItemId;
use App\Domain\Gender\Values\GenderSettings;
use App\Domain\Gender\Values\GenderUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class GenderRepository implements GenderContract
{
    private $genderModel;

    public function __construct()
    {
        $this->genderModel = new GenderModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $genders = $this->genderModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 10)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $genders->paginate($perPage ?? $genders->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los generos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los generos: ' . $e->getMessage());
        }
    }

    public function find(GenderId $genderId): ?Gender
    {
        try {
            $model = $this->genderModel->withTrashed()
                ->with(['item'])->where('item_id', 10)
                ->whereKey($genderId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El genero no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el genero: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el genero: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Gender
    {
        return new Gender(
            new GenderId($model->id),
            new GenderItemId($model->item?->id ?? $model->item_id),
            new GenderName($model->name),
            new GenderDescription($model->description),
            new GenderSettings($model->settings),
            new GenderCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new GenderUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new GenderDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
