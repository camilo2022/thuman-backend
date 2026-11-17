<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\TestTechnical\Contracts\TestTechnicalContract;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalApplicationId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalCreatedAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalRelationships;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStatus;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalUpdatedAt;
use App\Domain\Vacant\Application\TestTechnical\TestTechnical;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalAnswers;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalEndDate;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalFormId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalIndication;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderId;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderObservation;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalLeaderStatus;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalQuestions;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartAt;
use App\Domain\Vacant\Application\TestTechnical\Values\TestTechnicalStartDate;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalAllRequest;
use App\Http\Requests\Vacant\Application\TestTechnical\TestTechnicalFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\TestTechnical as TestTechnicalModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TestTechnicalRepository implements TestTechnicalContract
{
    private $testTechnicalModel;

    public function __construct()
    {
        $this->testTechnicalModel = new TestTechnicalModel;
    }

    public function all(string|null $search = null, int|null $perPage, TestTechnicalAllRequest $request): LengthAwarePaginator
    {
        try {
            $testTechnicals = $this->testTechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'leader',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'form.form_type'
                ])
                ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::TEST]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $testTechnicals->paginate($perPage ?? $testTechnicals->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de las pruebas técnicas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de las pruebas técnicas: ' . $e->getMessage());
        }
    }

    public function find(TestTechnicalFindRequest $request, TestTechnicalId $testTechnicalId): ?TestTechnical
    {
        try {
            $model = $this->testTechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'leader',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'form.form_type'
                ])
                ->when($testTechnicalId->value(), function ($query) use ($testTechnicalId) {
                    $query->where('id', $testTechnicalId->value());
                })
                ->when(!$testTechnicalId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de la prueba técnica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de la prueba técnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de la prueba técnica: ' . $e->getMessage());
        }
    }

    public function store(TestTechnical $testTechnical): TestTechnical
    {
        try {
            $model = $this->testTechnicalModel
                ->create($this->mapToModelData($testTechnical));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de la prueba técnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de la prueba técnica: ' . $e->getMessage());
        }
    }

    public function update(TestTechnical $vacant, TestTechnicalId $vacantId): TestTechnical
    {
        try {
            $model = tap($this->testTechnicalModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de la prueba técnica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de la prueba técnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de la prueba técnica: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): TestTechnical
    {
        return new TestTechnical(
            new TestTechnicalId($model->id),
            new TestTechnicalApplicationId($model->application?->id ?? $model->application_id),
            new TestTechnicalStartDate($model->start_date?->format('Y-m-d')),
            new TestTechnicalEndDate($model->end_date?->format('Y-m-d')),
            new TestTechnicalFormId($model->form_id),
            new TestTechnicalQuestions($model->questions),
            new TestTechnicalAnswers($model->answers),
            new TestTechnicalLeaderObservation($model->leader_observation),
            new TestTechnicalLeaderId($model->leader_id),
            new TestTechnicalLeaderStatus($model->leader_status),
            new TestTechnicalStartAt($model->start_at?->format('Y-m-d H:i:s')),
            new TestTechnicalEndAt($model->end_at?->format('Y-m-d H:i:s')),
            new TestTechnicalIndication($model->indication),
            new TestTechnicalStatus($model->status),
            new TestTechnicalCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new TestTechnicalUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new TestTechnicalRelationships($model->application, $model->form, $model->leader)
        );
    }

    private function mapToModelData(TestTechnical $testTechnical): array
    {
        $array = [];

        if ($testTechnical->applicationId()->value()) {
            $array['application_id'] = $testTechnical->applicationId()->value();
        }

        if ($testTechnical->startDate()->value()) {
            $array['start_date'] = $testTechnical->startDate()->value();
        }

        if ($testTechnical->endDate()->value()) {
            $array['end_date'] = $testTechnical->endDate()->value();
        }

        if ($testTechnical->formId()->value()) {
            $array['form_id'] = $testTechnical->formId()->value();
        }

        if ($testTechnical->questions()->value()) {
            $array['questions'] = $testTechnical->questions()->value();
        }

        if ($testTechnical->answers()->value()) {
            $array['answers'] = $testTechnical->answers()->value();
        }

        if ($testTechnical->leaderObservation()->value()) {
            $array['leader_observation'] = $testTechnical->leaderObservation()->value();
        }

        if ($testTechnical->leaderId()->value()) {
            $array['leader_id'] = $testTechnical->leaderId()->value();
        }

        if ($testTechnical->leaderStatus()->value()) {
            $array['leader_status'] = $testTechnical->leaderStatus()->value();
        }

        if ($testTechnical->startAt()->value()) {
            $array['start_at'] = $testTechnical->startAt()->value();
        }

        if ($testTechnical->endAt()->value()) {
            $array['end_at'] = $testTechnical->endAt()->value();
        }

        if ($testTechnical->indication()->value()) {
            $array['indication'] = $testTechnical->indication()->value();
        }

        if ($testTechnical->status()->value()) {
            $array['status'] = $testTechnical->status()->value();
        }

        return $array;
    }
}
