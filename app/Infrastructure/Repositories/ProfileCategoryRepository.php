<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\ProfileCategory\Contracts\ProfileCategoryContract;
use App\Models\ProfileCategory as ProfileCategoryModel;
use App\Domain\ProfileCategory\ProfileCategory;
use App\Domain\ProfileCategory\Values\ProfileCategoryCreatedAt;
use App\Domain\ProfileCategory\Values\ProfileCategoryDeletedAt;
use App\Domain\ProfileCategory\Values\ProfileCategoryId;
use App\Domain\ProfileCategory\Values\ProfileCategoryName;
use App\Domain\ProfileCategory\Values\ProfileCategoryDescription;
use App\Domain\ProfileCategory\Values\ProfileCategoryItemId;
use App\Domain\ProfileCategory\Values\ProfileCategorySettings;
use App\Domain\ProfileCategory\Values\ProfileCategoryUpdatedAt;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProfileCategoryRepository implements ProfileCategoryContract
{
    private $profileCategoryModel;

    public function __construct()
    {
        $this->profileCategoryModel = new ProfileCategoryModel;
    }

    public function all(string|null $search = null, int|null $perPage): LengthAwarePaginator
    {
        try {
            $profileCategories = $this->profileCategoryModel->withTrashed()
                ->with(['item'])
                ->where('item_id', 13)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $profileCategories->paginate($perPage ?? $profileCategories->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las categorias de perfil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las categorias de perfil: ' . $e->getMessage());
        }
    }

    public function find(ProfileCategoryId $profileCategoryId): ?ProfileCategory
    {
        try {
            $model = $this->profileCategoryModel->withTrashed()
                ->with(['item'])->where('item_id', 13)
                ->whereKey($profileCategoryId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El categoria de perfil no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el categoria de perfil: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el categoria de perfil: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): ProfileCategory
    {
        return new ProfileCategory(
            new ProfileCategoryId($model->id),
            new ProfileCategoryItemId($model->item?->id ?? $model->item_id),
            new ProfileCategoryName($model->name),
            new ProfileCategoryDescription($model->description),
            new ProfileCategorySettings($model->settings),
            new ProfileCategoryCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ProfileCategoryUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ProfileCategoryDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }
}
