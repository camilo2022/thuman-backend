<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\TestPsychotechnical\Contracts\TestPsychotechnicalContract;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalApplicationId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalCreatedAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalRelationships;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStatus;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalUpdatedAt;
use App\Domain\Vacant\Application\TestPsychotechnical\TestPsychotechnical;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalAnswers;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalEndDate;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalFormId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalIndication;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistId;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistObservation;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalPsychologistStatus;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalQuestions;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartAt;
use App\Domain\Vacant\Application\TestPsychotechnical\Values\TestPsychotechnicalStartDate;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalAllRequest;
use App\Http\Requests\Vacant\Application\TestPsychotechnical\TestPsychotechnicalFindRequest;
use App\Models\Application as ApplicationModel;
use App\Models\TestPsychotechnical as TestPsychotechnicalModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TestPsychotechnicalRepository implements TestPsychotechnicalContract
{
    private $testPsychotechnicalModel;

    public function __construct()
    {
        $this->testPsychotechnicalModel = new TestPsychotechnicalModel;
    }

    public function all(string|null $search = null, int|null $perPage, TestPsychotechnicalAllRequest $request): LengthAwarePaginator
    {
        try {
            $testPsychotechnicals = $this->testPsychotechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'psychologist',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'form.form_type'
                ])
                // ->whereHas('application', fn($r) => $r->whereIn('status', [ApplicationModel::TEST]))
                ->when($search, fn ($query) => $query->search($search))
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->where('status', $request->input('status'));
                })
                ->orderBy('id', 'asc');

            return $testPsychotechnicals->paginate($perPage ?? $testPsychotechnicals->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los registros de las pruebas psicotécnicas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los registros de las pruebas psicotécnicas: ' . $e->getMessage());
        }
    }

    public function find(TestPsychotechnicalFindRequest $request, TestPsychotechnicalId $testPsychotechnicalId): ?TestPsychotechnical
    {
        try {
            $model = $this->testPsychotechnicalModel
                ->with([
                    'application.person.photo', 'application.vacant.position.area', 'application.vacant.headquarter.entity.entity_type', 'psychologist',
                    'application.person.document_type', 'application.vacant.headquarter.city.departament.country', 'form.form_type'
                ])
                ->when($testPsychotechnicalId->value(), function ($query) use ($testPsychotechnicalId) {
                    $query->where('id', $testPsychotechnicalId->value());
                })
                ->when(!$testPsychotechnicalId->value(), function ($query) use ($request) {
                    $query->where('application_id', $request->integer('application_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de la prueba psicotécnica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el registro de la prueba psicotécnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el registro de la prueba psicotécnica: ' . $e->getMessage());
        }
    }

    public function store(TestPsychotechnical $testPsychotechnical): TestPsychotechnical
    {
        try {
            $model = $this->testPsychotechnicalModel
                ->create($this->mapToModelData($testPsychotechnical));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el registro de la prueba psicotécnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el registro de la prueba psicotécnica: ' . $e->getMessage());
        }
    }

    public function update(TestPsychotechnical $vacant, TestPsychotechnicalId $vacantId): TestPsychotechnical
    {
        try {
            $model = tap($this->testPsychotechnicalModel->findOrFail($vacantId->value()))
                ->update($this->mapToModelData($vacant))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El registro de la prueba psicotécnica no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el registro de la prueba psicotécnica: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el registro de la prueba psicotécnica: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): TestPsychotechnical
    {
        return new TestPsychotechnical(
            new TestPsychotechnicalId($model->id),
            new TestPsychotechnicalApplicationId($model->application?->id ?? $model->application_id),
            new TestPsychotechnicalStartDate($model->start_date?->format('Y-m-d')),
            new TestPsychotechnicalEndDate($model->end_date?->format('Y-m-d')),
            new TestPsychotechnicalFormId($model->form_id),
            new TestPsychotechnicalQuestions($model->questions),
            new TestPsychotechnicalAnswers($model->answers),
            new TestPsychotechnicalPsychologistObservation($model->psychologist_observation),
            new TestPsychotechnicalPsychologistId($model->psychologist_id),
            new TestPsychotechnicalPsychologistStatus($model->psychologist_status),
            new TestPsychotechnicalStartAt($model->start_at?->format('Y-m-d H:i:s')),
            new TestPsychotechnicalEndAt($model->date_at?->format('Y-m-d H:i:s')),
            new TestPsychotechnicalIndication($model->indication),
            new TestPsychotechnicalStatus($model->status),
            new TestPsychotechnicalCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new TestPsychotechnicalUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new TestPsychotechnicalRelationships($model->application, $model->form, $model->psychologist)
        );
    }

    private function mapToModelData(TestPsychotechnical $testPsychotechnical): array
    {
        $array = [];

        if ($testPsychotechnical->applicationId()->value()) {
            $array['application_id'] = $testPsychotechnical->applicationId()->value();
        }

        if ($testPsychotechnical->startDate()->value()) {
            $array['start_date'] = $testPsychotechnical->startDate()->value();
        }

        if ($testPsychotechnical->endDate()->value()) {
            $array['end_date'] = $testPsychotechnical->endDate()->value();
        }

        if ($testPsychotechnical->formId()->value()) {
            $array['form_id'] = $testPsychotechnical->formId()->value();
        }

        if ($testPsychotechnical->questions()->value()) {
            $array['questions'] = $testPsychotechnical->questions()->value();
        }

        if ($testPsychotechnical->answers()->value()) {
            $array['answers'] = $testPsychotechnical->answers()->value();
        }

        if ($testPsychotechnical->psychologistObservation()->value()) {
            $array['psychologist_observation'] = $testPsychotechnical->psychologistObservation()->value();
        }

        if ($testPsychotechnical->psychologistId()->value()) {
            $array['psychologist_id'] = $testPsychotechnical->psychologistId()->value();
        }

        if ($testPsychotechnical->psychologistStatus()->value()) {
            $array['psychologist_status'] = $testPsychotechnical->psychologistStatus()->value();
        }

        if ($testPsychotechnical->startAt()->value()) {
            $array['start_at'] = $testPsychotechnical->startAt()->value();
        }

        if ($testPsychotechnical->endAt()->value()) {
            $array['end_at'] = $testPsychotechnical->endAt()->value();
        }

        if ($testPsychotechnical->indication()->value()) {
            $array['indication'] = $testPsychotechnical->indication()->value();
        }

        if ($testPsychotechnical->status()->value()) {
            $array['status'] = $testPsychotechnical->status()->value();
        }

        return $array;
    }
}
