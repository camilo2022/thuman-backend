<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contract\Policy\Contracts\PolicyContract;
use App\Domain\Contract\Policy\File;
use App\Models\Policy as PolicyModel;
use App\Models\File as FileModel;
use App\Models\Contract as ContractModel;
use App\Domain\Contract\Policy\Policy;
use App\Domain\Contract\Policy\Values\FileCreatedAt;
use App\Domain\Contract\Policy\Values\FileDeletedAt;
use App\Domain\Contract\Policy\Values\FileExtension;
use App\Domain\Contract\Policy\Values\FileFileSubtypeId;
use App\Domain\Contract\Policy\Values\FileFileTypeId;
use App\Domain\Contract\Policy\Values\FileId;
use App\Domain\Contract\Policy\Values\FileMetadata;
use App\Domain\Contract\Policy\Values\FileMime;
use App\Domain\Contract\Policy\Values\FileModelId;
use App\Domain\Contract\Policy\Values\FileModelType;
use App\Domain\Contract\Policy\Values\FileName;
use App\Domain\Contract\Policy\Values\FilePath;
use App\Domain\Contract\Policy\Values\FileSize;
use App\Domain\Contract\Policy\Values\FileUpdatedAt;
use App\Domain\Contract\Policy\Values\PolicyCode;
use App\Domain\Contract\Policy\Values\PolicyCreatedAt;
use App\Domain\Contract\Policy\Values\PolicyId;
use App\Domain\Contract\Policy\Values\PolicyEndDate;
use App\Domain\Contract\Policy\Values\PolicyInsuredValue;
use App\Domain\Contract\Policy\Values\PolicyModelId;
use App\Domain\Contract\Policy\Values\PolicyModelType;
use App\Domain\Contract\Policy\Values\PolicyPolicyTypeId;
use App\Domain\Contract\Policy\Values\PolicyRelationships;
use App\Domain\Contract\Policy\Values\PolicyRiskManagerId;
use App\Domain\Contract\Policy\Values\PolicyStartDate;
use App\Domain\Contract\Policy\Values\PolicyStatus;
use App\Domain\Contract\Policy\Values\PolicyUpdatedAt;
use App\Domain\Contract\Policy\Values\PolicyUuid;
use App\Domain\Contract\Policy\Values\PolicyValue;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Policy\PolicyAllRequest;
use App\Http\Requests\Contract\Policy\PolicyFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PolicyRepository implements PolicyContract
{
    private $policyModel;
    private $fileModel;

    public function __construct()
    {
        $this->policyModel = new PolicyModel;
        $this->fileModel = new FileModel;
    }

    public function all(string|null $search = null, int|null $perPage, PolicyAllRequest $request, ContractId $contractId): LengthAwarePaginator
    {
        try {
            $policies = $this->policyModel
                ->with(['model', 'risk_manager', 'policy_type'])
                ->whereHasMorph('model', [ContractModel::class], function ($query) use ($contractId) {
                    $query->where('model_id', $contractId->value());
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $policies->paginate($perPage ?? $policies->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las pólizas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las pólizas: ' . $e->getMessage());
        }
    }

    public function find(PolicyFindRequest $request, PolicyId $policyId): ?Policy
    {
        try {
            $model = $this->policyModel
                ->with(['model', 'risk_manager', 'policy_type'])
                ->where($policyId->value() ? 'id' : 'uuid', $policyId->value() ?? $request->input('policy_uuid'))
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La póliza no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la póliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la póliza: ' . $e->getMessage());
        }
    }

    public function store(Policy $policy): Policy
    {
        try {
            $model = $this->policyModel
                ->create($this->mapToModelData($policy));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la póliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la póliza: ' . $e->getMessage());
        }
    }

    public function update(Policy $policy, PolicyId $policyId): Policy
    {
        try {
            $model = tap($this->policyModel->findOrFail($policyId->value()))
                ->update($this->mapToModelData($policy))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La póliza no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la póliza: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la póliza: ' . $e->getMessage());
        }
    }

    public function file(File $file): File
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataFile($file, 'keys'), $this->mapToModelDataFile($file, 'data'));

            return $this->mapToEntityFile($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La firma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar la firma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar la firma: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Policy
    {
        return new Policy(
            new PolicyId($model->id),
            new PolicyUuid($model->uuid),
            new PolicyModelId($model->model_id),
            new PolicyModelType($model->model_type),
            new PolicyRiskManagerId($model->risk_manager?->id ?? $model->risk_manager_id),
            new PolicyPolicyTypeId($model->policy_type?->id ?? $model->policy_type_id),
            new PolicyCode($model->code),
            new PolicyValue($model->value),
            new PolicyInsuredValue($model->insured_value),
            new PolicyStartDate($model->start_date?->format('Y-m-d')),
            new PolicyEndDate($model->end_date?->format('Y-m-d')),
            new PolicyStatus($model->position?->id ?? $model->position_id),
            new PolicyCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PolicyUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PolicyRelationships($model->model, $model->risk_manager, $model->policy_type)
        );
    }

    private function mapToEntityFile($model): File
    {
        return new File(
            new FileId($model->id),
            new FileModelId($model->model?->id ?? $model->model_id),
            new FileModelType($model->model_type),
            new FileFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new FileFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new FileName($model->name),
            new FilePath($model->path),
            new FileMime($model->mime),
            new FileExtension($model->extension),
            new FileSize($model->size),
            new FileMetadata($model->metadata),
            new FileCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new FileUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new FileDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Policy $policy): array
    {
        $array = [];

        if ($policy->modelId()->value()) {
            $array['model_id'] = $policy->modelId()->value();
        }

        if ($policy->modelType()->value()) {
            $array['model_type'] = $policy->modelType()->value();
        }

        if ($policy->riskManagerId()->value()) {
            $array['risk_manager_id'] = $policy->riskManagerId()->value();
        }

        if ($policy->policyTypeId()->value()) {
            $array['policy_type_id'] = $policy->policyTypeId()->value();
        }

        if ($policy->code()->value()) {
            $array['code'] = $policy->code()->value();
        }

        if ($policy->value()->value()) {
            $array['value'] = $policy->value()->value();
        }

        if ($policy->insuredValue()->value()) {
            $array['insured_value'] = $policy->insuredValue()->value();
        }

        if ($policy->startDate()->value()) {
            $array['start_date'] = $policy->startDate()->value();
        }

        if ($policy->endDate()->value()) {
            $array['end_date'] = $policy->endDate()->value();
        }

        return $array;
    }

    private function mapToModelDataFile(File $file, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($file->modelId()->value()) {
            $array['keys']['model_id'] = $file->modelId()->value();
        }

        if ($file->modelType()->value()) {
            $array['keys']['model_type'] = $file->modelType()->value();
        }

        if ($file->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $file->fileTypeId()->value();
        }

        if ($file->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $file->fileSubtypeId()->value();
        }

        if ($file->name()->value()) {
            $array['data']['name'] = $file->name()->value();
        }

        if ($file->path()->value()) {
            $array['data']['path'] = $file->path()->value();
        }

        if ($file->mime()->value()) {
            $array['data']['mime'] = $file->mime()->value();
        }

        if ($file->extension()->value()) {
            $array['data']['extension'] = $file->extension()->value();
        }

        if ($file->size()->value()) {
            $array['data']['size'] = $file->size()->value();
        }

        if ($file->metadata()->value()) {
            $array['data']['metadata'] = $file->metadata()->value();
        }

        return $array[$index];
    }
}
