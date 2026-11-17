<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contract\Addendum\Contracts\AddendumContract;
use App\Models\Addendum as AddendumModel;
use App\Models\File as FileModel;
use App\Domain\Contract\Addendum\Addendum;
use App\Domain\Contract\Addendum\Image;
use App\Domain\Contract\Addendum\Values\AddendumAddendumTypeId;
use App\Domain\Contract\Addendum\Values\AddendumContractId;
use App\Domain\Contract\Addendum\Values\AddendumContractTypeId;
use App\Domain\Contract\Addendum\Values\AddendumCreatedAt;
use App\Domain\Contract\Addendum\Values\AddendumDeletedAt;
use App\Domain\Contract\Addendum\Values\AddendumId;
use App\Domain\Contract\Addendum\Values\AddendumEndDate;
use App\Domain\Contract\Addendum\Values\AddendumFileSubtypesId;
use App\Domain\Contract\Addendum\Values\AddendumHeadquarterId;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumLegalRepresentativeStatus;
use App\Domain\Contract\Addendum\Values\AddendumPaymentPeriodId;
use App\Domain\Contract\Addendum\Values\AddendumPersonObservation;
use App\Domain\Contract\Addendum\Values\AddendumPersonSignedAt;
use App\Domain\Contract\Addendum\Values\AddendumPersonStatus;
use App\Domain\Contract\Addendum\Values\AddendumPositionId;
use App\Domain\Contract\Addendum\Values\AddendumRelationships;
use App\Domain\Contract\Addendum\Values\AddendumSalary;
use App\Domain\Contract\Addendum\Values\AddendumSettings;
use App\Domain\Contract\Addendum\Values\AddendumStartDate;
use App\Domain\Contract\Addendum\Values\AddendumUpdatedAt;
use App\Domain\Contract\Addendum\Values\AddendumUuid;
use App\Domain\Contract\Addendum\Values\ImageCreatedAt;
use App\Domain\Contract\Addendum\Values\ImageDeletedAt;
use App\Domain\Contract\Addendum\Values\ImageExtension;
use App\Domain\Contract\Addendum\Values\ImageFileSubtypeId;
use App\Domain\Contract\Addendum\Values\ImageFileTypeId;
use App\Domain\Contract\Addendum\Values\ImageId;
use App\Domain\Contract\Addendum\Values\ImageMetadata;
use App\Domain\Contract\Addendum\Values\ImageMime;
use App\Domain\Contract\Addendum\Values\ImageModelId;
use App\Domain\Contract\Addendum\Values\ImageModelType;
use App\Domain\Contract\Addendum\Values\ImageName;
use App\Domain\Contract\Addendum\Values\ImagePath;
use App\Domain\Contract\Addendum\Values\ImageSize;
use App\Domain\Contract\Addendum\Values\ImageUpdatedAt;
use App\Domain\Contract\Values\ContractId;
use App\Http\Requests\Contract\Addendum\AddendumAllRequest;
use App\Http\Requests\Contract\Addendum\AddendumFindRequest;
use App\Http\Requests\Contract\Addendum\AddendumNotifyTokenRequest;
use App\Http\Requests\Contract\Addendum\AddendumVerifyTokenRequest;
use App\Service\Contract\Addendum\AddendumNotification;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class AddendumRepository implements AddendumContract
{
    private $addendumModel;
    private $fileModel;
    private $contractNotification;

    public function __construct()
    {
        $this->addendumModel = new AddendumModel;
        $this->fileModel = new FileModel;
        $this->contractNotification = new AddendumNotification;
    }

    public function all(string|null $search = null, int|null $perPage, AddendumAllRequest $request, ContractId $contractId): LengthAwarePaginator
    {
        try {
            $contracts = $this->addendumModel->withTrashed()
                ->with([
                    'contract.person.document_type', 'contract.person.photo', 'contract.contract_type', 'contract.position.area', 'contract.payment_period',
                    'contract.file_subtypes', 'contract_type', 'position.area', 'payment_period', 'file_subtypes'
                ])
                ->where('contract_id', $contractId->value())
                ->when($request->filled('person_status'), function ($q) use ($request) {
                    $q->where('person_status', $request->input('person_status'));
                })
                ->when($request->filled('legal_representative_status'), function ($q) use ($request) {
                    $q->where('legal_representative_status', $request->input('legal_representative_status'));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $contracts->paginate($perPage ?? $contracts->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los otro síes: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los otro síes: ' . $e->getMessage());
        }
    }

    public function find(AddendumFindRequest $request, AddendumId $addendumId): ?Addendum
    {
        try {
            $model = $this->addendumModel
                ->with([
                    'contract.person.document_type', 'contract.person.photo', 'contract.contract_type', 'contract.position.area', 'contract.payment_period',
                    'contract.file_subtypes', 'contract_type', 'position.area', 'payment_period', 'file_subtypes'
                ])
                ->where($addendumId->value() ? 'id' : 'uuid', $addendumId->value() ?? $request->input('contract_uuid'))
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El otro sí no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el otro sí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el otro sí: ' . $e->getMessage());
        }
    }

    public function store(Addendum $addendum): Addendum
    {
        try {
            $model = $this->addendumModel
                ->create($this->mapToModelData($addendum));

            $model->file_subtypes()->sync(array_filter(
                $addendum->fileSubtypesId()->value()
            ));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el otro sí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el otro sí: ' . $e->getMessage());
        }
    }

    public function update(Addendum $addendum, AddendumId $addendumId): Addendum
    {
        try {
            $model = tap($this->addendumModel->findOrFail($addendumId->value()))
                ->update($this->mapToModelData($addendum))
                ->refresh();

            $model->file_subtypes()->sync(array_filter(
                $addendum->fileSubtypesId()->value()
            ));

            if($model->person_status === AddendumModel::SIGNED and $model->legal_representative_status === AddendumModel::APPROVED and !$model->person_signature){
                $this->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($addendumId->value()),
                        new ImageModelType(AddendumModel::class),
                        new ImageFileTypeId(100),
                        new ImageFileSubtypeId($model->person->signature->file_subtype_id),
                        new ImageName($model->person->signature->name),
                        new ImagePath($model->person->signature->path),
                        new ImageMime($model->person->signature->mime),
                        new ImageExtension($model->person->signature->extension),
                        new ImageSize($model->person->signature->size),
                        new ImageMetadata($model->person->signature->metadata)
                    )
                );
            }

            if($model->legal_representative_status === AddendumModel::SIGNED and !$model->legal_representative_signature){
                $this->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($addendumId->value()),
                        new ImageModelType(AddendumModel::class),
                        new ImageFileTypeId(97),
                        new ImageFileSubtypeId($model->headquarter->entity->person->signature->file_subtype_id),
                        new ImageName($model->headquarter->entity->person->signature->name),
                        new ImagePath($model->headquarter->entity->person->signature->path),
                        new ImageMime($model->headquarter->entity->person->signature->mime),
                        new ImageExtension($model->headquarter->entity->person->signature->extension),
                        new ImageSize($model->headquarter->entity->person->signature->size),
                        new ImageMetadata($model->headquarter->entity->person->signature->metadata)
                    )
                );
            }

            if($model->person_status === AddendumModel::WITHDRAWNED or $model->legal_representative_status === AddendumModel::REJECTED) {
                $model->delete();
            }

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El otro sí no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el otro sí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el otro sí: ' . $e->getMessage());
        }
    }

    public function image(Image $image): Image
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataImage($image, 'keys'), $this->mapToModelDataImage($image, 'data'));

            return $this->mapToEntityImage($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La firma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar la firma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar la firma: ' . $e->getMessage());
        }
    }

    public function notifyToken(AddendumNotifyTokenRequest $request, AddendumId $addendumId): void
    {
        try {
            $model = $this->addendumModel
                ->when($addendumId->value(), function ($query) use ($addendumId) {
                    $query->where('id', $addendumId->value());
                })
                ->when(!$addendumId->value(), function ($query) use ($request) {
                    $query->when($request->filled('addendum_uuid'), function ($query) use ($request) {
                        $query->where('uuid', $request->input('addendum_uuid'));
                    });
                })
                ->firstOrFail();

            $verification = $model->verifications()->where('verified', false)->latest()->first();

            if ($verification && !$verification->isExpired()) {
                throw new HttpException(409, 'Ya existe un token activo. No puede solicitar uno nuevo hasta que el actual expire o sea utilizado.');
            }

            $verification = $model->notifyToken();

            $emailId = $this->contractNotification->sendEmail($model->person->email, implode(' ', array_filter([$model->person->first_name, $model->person->second_name, $model->person->first_last_name, $model->person->second_last_name])), [ 'code' => $verification->code ]);
            $verification->attachExternalId($emailId);

        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El otro sí no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el otro sí: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el token de acceso: ' . $e->getMessage());
        }
    }

    public function verifyToken(AddendumVerifyTokenRequest $request, AddendumId $addendumId): ?Addendum
    {
        try {
            $model = $this->addendumModel->findOrFail($addendumId->value());

            $verification = $model->verifications()->where('code', $request->input('code'))->where('verified', false)->latest()->first();

            if (!$verification) {
                throw new HttpException(422, 'El token ingresado no es válido.');
            }

            if ($verification->isExpired()) {
                throw new HttpException(410, 'El token ha expirado. Solicite uno nuevo.');
            }

            $verification->verifyCode();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El otro sí no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al verificar el token: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Addendum
    {
        return new Addendum(
            new AddendumId($model->id),
            new AddendumUuid($model->uuid),
            new AddendumContractId($model->contract_id),
            new AddendumAddendumTypeId($model->addendum_type_id),
            new AddendumHeadquarterId($model->headquarter_id),
            new AddendumContractTypeId($model->contract_type_id),
            new AddendumPositionId($model->position_id),
            new AddendumSalary($model->salary),
            new AddendumPaymentPeriodId($model->payment_period_id),
            new AddendumStartDate($model->start_date?->format('Y-m-d')),
            new AddendumEndDate($model->end_date?->format('Y-m-d')),
            new AddendumPersonObservation($model->person_observation),
            new AddendumPersonSignedAt($model->person_signed_at?->format('Y-m-d H:i:s')),
            new AddendumPersonStatus($model->person_status),
            new AddendumLegalRepresentativeSignedAt($model->legal_representative_signed_at?->format('Y-m-d H:i:s')),
            new AddendumLegalRepresentativeStatus($model->legal_representative_status),
            new AddendumSettings($model->settings),
            new AddendumFileSubtypesId($model->file_subtypes->modelKeys()),
            new AddendumCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new AddendumUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new AddendumDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new AddendumRelationships($model->contract, $model->addendum_type, $model->headquarter, $model->contract_type, $model->position, $model->payment_period, $model->signed_person, $model->signed_legal_representative)
        );
    }

    private function mapToEntityImage($model): Image
    {
        return new Image(
            new ImageId($model->id),
            new ImageModelId($model->model?->id ?? $model->model_id),
            new ImageModelType($model->model_type),
            new ImageFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new ImageFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new ImageName($model->name),
            new ImagePath($model->path),
            new ImageMime($model->mime),
            new ImageExtension($model->extension),
            new ImageSize($model->size),
            new ImageMetadata($model->metadata),
            new ImageCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ImageUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ImageDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Addendum $addendum): array
    {
        $array = [];

        if ($addendum->contractId()->value()) {
            $array['contract_id'] = $addendum->contractId()->value();
        }

        if ($addendum->addendumTypeId()->value()) {
            $array['addendum_type_id'] = $addendum->addendumTypeId()->value();
        }

        if ($addendum->headquarterId()->value()) {
            $array['headquarter_id'] = $addendum->headquarterId()->value();
        }

        if ($addendum->contractTypeId()->value()) {
            $array['contract_type_id'] = $addendum->contractTypeId()->value();
        }

        if ($addendum->positionId()->value()) {
            $array['position_id'] = $addendum->positionId()->value();
        }

        if ($addendum->salary()->value()) {
            $array['salary'] = $addendum->salary()->value();
        }

        if ($addendum->paymentPeriodId()->value()) {
            $array['payment_period_id'] = $addendum->paymentPeriodId()->value();
        }

        if ($addendum->startDate()->value()) {
            $array['start_date'] = $addendum->startDate()->value();
        }

        if ($addendum->endDate()->value()) {
            $array['end_date'] = $addendum->endDate()->value();
        }

        if ($addendum->personObservation()->value()) {
            $array['person_observation'] = $addendum->personObservation()->value();
        }

        if ($addendum->personSignedAt()->value()) {
            $array['person_signed_at'] = $addendum->personSignedAt()->value();
        }

        if ($addendum->personStatus()->value()) {
            $array['person_status'] = $addendum->personStatus()->value();
        }

        if ($addendum->legalRepresentativeSignedAt()->value()) {
            $array['legal_representative_signed_at'] = $addendum->legalRepresentativeSignedAt()->value();
        }

        if ($addendum->legalRepresentativeStatus()->value()) {
            $array['legal_representative_status'] = $addendum->legalRepresentativeStatus()->value();
        }

        if ($addendum->settings()->value()) {
            $array['settings'] = $addendum->settings()->value();
        }

        return $array;
    }

    private function mapToModelDataImage(Image $image, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($image->modelId()->value()) {
            $array['keys']['model_id'] = $image->modelId()->value();
        }

        if ($image->modelType()->value()) {
            $array['keys']['model_type'] = $image->modelType()->value();
        }

        if ($image->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $image->fileTypeId()->value();
        }

        if ($image->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $image->fileSubtypeId()->value();
        }

        if ($image->name()->value()) {
            $array['data']['name'] = $image->name()->value();
        }

        if ($image->path()->value()) {
            $array['data']['path'] = $image->path()->value();
        }

        if ($image->mime()->value()) {
            $array['data']['mime'] = $image->mime()->value();
        }

        if ($image->extension()->value()) {
            $array['data']['extension'] = $image->extension()->value();
        }

        if ($image->size()->value()) {
            $array['data']['size'] = $image->size()->value();
        }

        if ($image->metadata()->value()) {
            $array['data']['metadata'] = $image->metadata()->value();
        }

        return $array[$index];
    }
}
