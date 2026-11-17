<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\EpidemiologicalProgram\Contracts\EpidemiologicalProgramContract;
use App\Models\EpidemiologicalProgram as EpidemiologicalProgramModel;
use App\Domain\EpidemiologicalProgram\EpidemiologicalProgram;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramCreatedAt;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramDeletedAt;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramName;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramDescription;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramItemId;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramSettings;
use App\Domain\EpidemiologicalProgram\Values\EpidemiologicalProgramUpdatedAt;
use App\Http\Requests\EpidemiologicalProgram\EpidemiologicalProgramAllRequest;
use App\Models\ExamMedical;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EpidemiologicalProgramRepository implements EpidemiologicalProgramContract
{
    private $epidemiologicalProgramModel;

    public function __construct()
    {
        $this->epidemiologicalProgramModel = new EpidemiologicalProgramModel;
    }

    public function all(string|null $search = null, int|null $perPage, EpidemiologicalProgramAllRequest $request): LengthAwarePaginator
    {
        try {
            $epidemiologicalPrograms = $this->epidemiologicalProgramModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['item'])
                ->where('item_id', 32)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $epidemiologicalPrograms->paginate($perPage ?? $epidemiologicalPrograms->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los programas de vigilacia epidemiológicas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los programas de vigilacia epidemiológicas: ' . $e->getMessage());
        }
    }

    public function find(EpidemiologicalProgramId $epidemiologicalProgramId): ?EpidemiologicalProgram
    {
        try {
            $model = $this->epidemiologicalProgramModel->withTrashed()
                ->with(['item'])->where('item_id', 32)
                ->whereKey($epidemiologicalProgramId->value())
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El programa de vigilacia epidemiológica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        }
    }

    public function store(EpidemiologicalProgram $epidemiologicalProgram): EpidemiologicalProgram
    {
        try {
            $model = $this->epidemiologicalProgramModel
                ->create($this->mapToModelData($epidemiologicalProgram));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        }
    }

    public function update(EpidemiologicalProgram $epidemiologicalProgram, EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram
    {
        try {
            $model = tap($this->epidemiologicalProgramModel->where('item_id', 32)->whereKey($epidemiologicalProgramId->value())->firstOrFail())
                ->update($this->mapToModelData($epidemiologicalProgram))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El programa de vigilacia epidemiológica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        }
    }

    public function delete(EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram
    {
        try {
            $model = $this->epidemiologicalProgramModel->where('item_id', 32)->whereKey($epidemiologicalProgramId->value())->firstOrFail();

            $isUsed = ExamMedical::where('epidemiological_program_id', $epidemiologicalProgramId->value())->exists();

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El programa de vigilacia epidemiológica no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        }
    }

    public function restore(EpidemiologicalProgramId $epidemiologicalProgramId): EpidemiologicalProgram
    {
        try {
            $model = tap($this->epidemiologicalProgramModel->withTrashed()->where('item_id', 32)->whereKey($epidemiologicalProgramId->value())->firstOrFail(), fn($epidemiologicalProgram) => $epidemiologicalProgram->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El programa de vigilacia epidemiológica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el programa de vigilacia epidemiológica: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): EpidemiologicalProgram
    {
        return new EpidemiologicalProgram(
            new EpidemiologicalProgramId($model->id),
            new EpidemiologicalProgramItemId($model->item?->id ?? $model->item_id),
            new EpidemiologicalProgramName($model->name),
            new EpidemiologicalProgramDescription($model->description),
            new EpidemiologicalProgramSettings($model->settings),
            new EpidemiologicalProgramCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EpidemiologicalProgramUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EpidemiologicalProgramDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(EpidemiologicalProgram $epidemiologicalProgram): array
    {
        $array = [];

        if ($epidemiologicalProgram->itemId()->value()) {
            $array['item_id'] = $epidemiologicalProgram->itemId()->value();
        }

        if ($epidemiologicalProgram->name()->value()) {
            $array['name'] = $epidemiologicalProgram->name()->value();
        }

        if ($epidemiologicalProgram->description()->value()) {
            $array['description'] = $epidemiologicalProgram->description()->value();
        }

        if ($epidemiologicalProgram->settings()->value()) {
            $array['settings'] = $epidemiologicalProgram->settings()->value();
        }

        return $array;
    }
}
