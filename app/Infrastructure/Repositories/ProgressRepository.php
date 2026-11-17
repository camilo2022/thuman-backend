<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Progress\Contracts\ProgressContract;
use App\Domain\Progress\Progress;
use App\Domain\Progress\Values\ProgressAnswers;
use App\Domain\Progress\Values\ProgressCreatedAt;
use App\Domain\Progress\Values\ProgressDeletedAt;
use App\Domain\Progress\Values\ProgressEndAt;
use App\Domain\Progress\Values\ProgressId;
use App\Domain\Progress\Values\ProgressPersonId;
use App\Domain\Progress\Values\ProgressQuestions;
use App\Domain\Progress\Values\ProgressRelationships;
use App\Domain\Progress\Values\ProgressResult;
use App\Domain\Progress\Values\ProgressStartAt;
use App\Domain\Progress\Values\ProgressStatus;
use App\Domain\Progress\Values\ProgressStepId;
use App\Domain\Progress\Values\ProgressUpdatedAt;
use App\Http\Requests\Progress\ProgressFindRequest;
use App\Models\Progress as ProgressModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ProgressRepository implements ProgressContract
{
    private $progressModel;

    public function __construct()
    {
        $this->progressModel = new ProgressModel;
    }

    public function find(ProgressFindRequest $request, ProgressId $progressId): ?Progress
    {
        try {
            $model = $this->progressModel
                ->with([
                    'person', 'step.induction'
                ])
                ->when($progressId->value(), function ($query) use ($progressId) {
                    $query->where('id', $progressId->value());
                })
                ->when(!$progressId->value(), function ($query) use ($request) {
                    $query->where('person_id', $request->integer('person_id'))
                        ->where('step_id', $request->integer('step_id'))
                        ->where('status', $request->input('status'))
                        ->latest('id');
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro del progreso del paso inductivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro del progreso del paso inductivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro del progreso del paso inductivo: ' . $e->getMessage());
        }
    }

    public function store(Progress $progress): Progress
    {
        try {
            $model = $this->progressModel
                ->create($this->mapToModelData($progress));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro del progreso del paso inductivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro del progreso del paso inductivo: ' . $e->getMessage());
        }
    }

    public function update(Progress $vacant, ProgressId $vacantId): Progress
    {
        try {
            $model = tap($this->progressModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro del progreso del paso inductivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro del progreso del paso inductivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro del progreso del paso inductivo: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Progress
    {
        return new Progress(
            new ProgressId($model->id),
            new ProgressPersonId($model->person?->id ?? $model->person_id),
            new ProgressStepId($model->step_id),
            new ProgressQuestions($model->questions),
            new ProgressAnswers($model->answers),
            new ProgressStartAt($model->start_at?->format('Y-m-d H:i:s')),
            new ProgressEndAt($model->end_at?->format('Y-m-d H:i:s')),
            new ProgressStatus($model->status),
            new ProgressResult($model->result),
            new ProgressCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ProgressUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ProgressDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ProgressRelationships($model->person, $model->step)
        );
    }

    private function mapToModelData(Progress $progress): array
    {
        $array = [];

        if ($progress->personId()->value()) {
            $array['person_id'] = $progress->personId()->value();
        }

        if ($progress->stepId()->value()) {
            $array['step_id'] = $progress->stepId()->value();
        }

        if ($progress->questions()->value()) {
            $array['questions'] = $progress->questions()->value();
        }

        if ($progress->answers()->value()) {
            $array['answers'] = $progress->answers()->value();
        }

        if ($progress->startAt()->value()) {
            $array['start_at'] = $progress->startAt()->value();
        }

        if ($progress->endAt()->value()) {
            $array['end_at'] = $progress->endAt()->value();
        }

        if ($progress->status()->value()) {
            $array['status'] = $progress->status()->value();
        }

        if ($progress->result()->value()) {
            $array['result'] = $progress->result()->value();
        }

        return $array;
    }
}
