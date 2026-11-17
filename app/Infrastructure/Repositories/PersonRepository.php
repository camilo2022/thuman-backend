<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\File;
use App\Models\Person as PersonModel;
use App\Models\File as FileModel;
use App\Domain\Person\Person;
use App\Domain\Person\Values\FileCreatedAt;
use App\Domain\Person\Values\FileDeletedAt;
use App\Domain\Person\Values\FileExtension;
use App\Domain\Person\Values\FileFileSubtypeId;
use App\Domain\Person\Values\FileFileTypeId;
use App\Domain\Person\Values\FileId;
use App\Domain\Person\Values\FileMetadata;
use App\Domain\Person\Values\FileMime;
use App\Domain\Person\Values\FileModelId;
use App\Domain\Person\Values\FileModelType;
use App\Domain\Person\Values\FileName;
use App\Domain\Person\Values\FilePath;
use App\Domain\Person\Values\FileSize;
use App\Domain\Person\Values\FileUpdatedAt;
use App\Domain\Person\Values\PersonAddress;
use App\Domain\Person\Values\PersonBirthCityId;
use App\Domain\Person\Values\PersonBirthDate;
use App\Domain\Person\Values\PersonBloodTypeId;
use App\Domain\Person\Values\PersonCityId;
use App\Domain\Person\Values\PersonCreatedAt;
use App\Domain\Person\Values\PersonDeletedAt;
use App\Domain\Person\Values\PersonDialingCountryId;
use App\Domain\Person\Values\PersonDocumentIssueCityId;
use App\Domain\Person\Values\PersonDocumentIssueDate;
use App\Domain\Person\Values\PersonDocumentNumber;
use App\Domain\Person\Values\PersonDocumentTypeId;
use App\Domain\Person\Values\PersonEmail;
use App\Domain\Person\Values\PersonEmailVerifiedAt;
use App\Domain\Person\Values\PersonEntityId;
use App\Domain\Person\Values\PersonFirstLastName;
use App\Domain\Person\Values\PersonFirstName;
use App\Domain\Person\Values\PersonGenderId;
use App\Domain\Person\Values\PersonId;
use App\Domain\Person\Values\PersonMaritalStatusId;
use App\Domain\Person\Values\PersonNeighborhood;
use App\Domain\Person\Values\PersonPhoneNumber;
use App\Domain\Person\Values\PersonPhoneNumberVerifiedAt;
use App\Domain\Person\Values\PersonRelationships;
use App\Domain\Person\Values\PersonSecondLastName;
use App\Domain\Person\Values\PersonSecondName;
use App\Domain\Person\Values\PersonSexId;
use App\Domain\Person\Values\PersonUpdatedAt;
use App\Domain\Person\Values\PersonVacantId;
use App\Http\Requests\Person\PersonAllRequest;
use App\Http\Requests\Person\PersonFindRequest;
use App\Http\Requests\Person\PersonVerifyEmailRequest;
use App\Http\Requests\Person\PersonVerifyPhoneNumberRequest;
use App\Jobs\NotificationJob;
use App\Models\Entity;
use App\Service\Person\PersonNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class PersonRepository implements PersonContract
{
    private $personModel;
    private $fileModel;
    private $personNotification;

    public function __construct()
    {
        $this->personModel = new PersonModel;
        $this->fileModel = new FileModel;
        $this->personNotification = new PersonNotification;
    }

    public function all(string|null $search = null, int|null $perPage, PersonAllRequest $request): LengthAwarePaginator
    {
        try {
            $people = $this->personModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with([
                    'birth_city.departament.country',
                    'document_issue_city.departament.country',
                    'city.departament.country',
                    'dialing_country',
                    'document_type',
                    'sex',
                    'gender',
                    'blood_type',
                    'marital_status',
                    'file.file_type',
                    'file.file_subtype',
                    'photo.file_type',
                    'photo.file_subtype',
                    'vacants',
                    'entities',
                    'signature.file_type',
                    'signature.file_subtype',
                    'employee'
                ])
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $people->paginate($perPage ?? $people->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las personas: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las personas: ' . $e->getMessage());
        }
    }

    public function find(PersonFindRequest $request, PersonId $personId): ?Person
    {
        try {
            $model = $this->personModel
                ->with([
                    'birth_city.departament.country',
                    'document_issue_city.departament.country',
                    'city.departament.country',
                    'dialing_country',
                    'document_type',
                    'sex',
                    'gender',
                    'blood_type',
                    'marital_status',
                    'file.file_type',
                    'file.file_subtype',
                    'photo.file_type',
                    'photo.file_subtype',
                    'vacants',
                    'entities',
                    'signature.file_type',
                    'signature.file_subtype',
                    'employee'
                ])
                ->when($personId->value(), function ($query) use ($personId) {
                    $query->where('id', $personId->value());
                })
                ->when(!$personId->value(), function ($query) use ($request) {
                    $query->when($request->filled('document_type_id'), function ($query) use ($request) {
                        $query->where('document_type_id', $request->integer('document_type_id'));
                    })
                        ->when($request->filled('document_number'), function ($query) use ($request) {
                            $query->where('document_number', $request->input('document_number'));
                        })
                        ->when($request->filled('phone_number'), function ($query) use ($request) {
                            $query->where('phone_number', $request->input('phone_number'));
                        })
                        ->when($request->filled('email'), function ($query) use ($request) {
                            $query->where('email', $request->input('email'));
                        });
                })
                ->when($request->filled('employee_id'), function ($q) use ($request) {
                    $q->whereHas('employee', function ($sq) use ($request) {
                        $sq->where('id', $request->integer('employee_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la persona: ' . $e->getMessage());
        }
    }

    public function store(Person $person): Person
    {
        try {
            $model = $this->personModel
                ->create($this->mapToModelData($person));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la persona: ' . $e->getMessage());
        }
    }

    public function update(Person $person, PersonId $personId): Person
    {
        try {
            $model = tap($this->personModel->findOrFail($personId->value()))
                ->update($this->mapToModelData($person))->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la persona: ' . $e->getMessage());
        }
    }

    public function toggle(Person $person, PersonId $personId): Person
    {
        try {
            $model = $this->personModel->findOrFail($personId->value());

            $model->vacants()->toggle(array_filter([
                $person->vacantId()->value()
            ]));

            $model->entities()->toggle(array_filter([
                $person->entityId()->value()
            ]));

            return $this->mapToEntity($model->refresh());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la persona: ' . $e->getMessage());
        }
    }

    public function delete(PersonId $personId): Person
    {
        try {
            $model = $this->personModel->findOrFail($personId->value());

            $isUsed = Entity::where('person_id', $model->id)->exists();

            if ((int) $model->id === 1) {
                throw new HttpException(
                    409,
                    'No es posible eliminar el usuario Admin.'
                );
            }

            if ($isUsed) {
                throw new HttpException(
                    409,
                    'El registro tiene datos asociados.'
                );
            }

            $model->delete();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la persona: ' . $e->getMessage());
        }
    }

    public function restore(PersonId $personId): Person
    {
        try {
            $model = tap($this->personModel->withTrashed()->findOrFail($personId->value()), fn($person) => $person->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El persona no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la persona: ' . $e->getMessage());
        }
    }

    public function file(File $file): File
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataFile($file, 'keys'), $this->mapToModelDataFile($file, 'data'));

            return $this->mapToEntityFile($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El archivo no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el archivo: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el archivo: ' . $e->getMessage());
        }
    }

    public function notifyEmail(PersonId $personId): void
    {
        try {
            $model = $this->personModel
                ->with(['document_type', 'document_issue_city.departament.country', 'birth_city.departament.country', 'dialing_country', 'city.departament.country', 'user.person.dialing_country',])
                ->findOrFail($personId->value());

            $verification = $model->verifications()->where('verified', false)->latest()->first();

            if ($verification && !$verification->isExpired()) {
                throw new HttpException(409, 'Ya existe un codigo activo. No puede solicitar uno nuevo hasta que el actual expire o sea utilizado.');
            }

            $verification = $model->notifyEmail();

            $data = (object) [
                'user' => $model->user,
                'code' => $verification->code
            ];

            $emailId = NotificationJob::dispatchSync(362, $data, [336], 'html');

            $verification->attachExternalId((string) $emailId);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar la notificación a la persona: ' . $e->getMessage());
        }
    }

    public function notifyPhoneNumber(PersonId $personId): void
    {
        try {
            $model = $this->personModel
                ->with(['document_type', 'document_issue_city.departament.country', 'birth_city.departament.country', 'dialing_country', 'city.departament.country'])
                ->findOrFail($personId->value());

            $verification = $model->verifications()->where('verified', false)->latest()->first();

            if ($verification && !$verification->isExpired()) {
                throw new HttpException(409, 'Ya existe un codigo activo. No puede solicitar uno nuevo hasta que el actual expire o sea utilizado.');
            }

            $verification = $model->notifyPhoneNumber();

            $data = (object) [
                'user' => $model->user,
                'code' => $verification->code
            ];

            $smsId = NotificationJob::dispatchSync(362, $data, [335], 'text');

            $verification->attachExternalId((string) $smsId);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la persona: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar la notificación a la persona: ' . $e->getMessage());
        }
    }

    public function verifyEmail(PersonVerifyEmailRequest $request, PersonId $personId): ?Person
    {
        try {
            $model = $this->personModel->findOrFail($personId->value());

            $verification = $model->verifications()->where('code', $request->input('code'))->where('verified', false)->latest()->first();

            if (!$verification) {
                throw new HttpException(422, 'El código ingresado no es válido.');
            }

            if ($verification->isExpired()) {
                throw new HttpException(410, 'El código ha expirado. Solicite uno nuevo.');
            }

            $verification->verifyCode();

            $model->email_verified_at = Carbon::now();
            $model->save();
            $person = $this->mapToEntity($model);

            $model = tap($this->personModel->findOrFail($personId->value()))
                ->update($this->mapToModelData($person))->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al verificar el código: ' . $e->getMessage());
        }
    }

    public function verifyPhoneNumber(PersonVerifyPhoneNumberRequest $request, PersonId $personId): ?Person
    {
        try {
            $model = $this->personModel->findOrFail($personId->value());

            $verification = $model->verifications()->where('code', $request->input('code'))->where('verified', false)->latest()->first();

            if (!$verification) {
                throw new HttpException(422, 'El código ingresado no es válido.');
            }

            if ($verification->isExpired()) {
                throw new HttpException(410, 'El código ha expirado. Solicite uno nuevo.');
            }

            $verification->verifyCode();

            $model->phone_number_verified_at = Carbon::now();
            $model->save();
            $person = $this->mapToEntity($model);

            $model = tap($this->personModel->findOrFail($personId->value()))
                ->update($this->mapToModelData($person))->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La persona no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al verificar el código: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Person
    {
        return new Person(
            new PersonId($model->id),
            new PersonFirstName($model->first_name),
            new PersonSecondName($model->second_name),
            new PersonFirstLastName($model->first_last_name),
            new PersonSecondLastName($model->second_last_name),
            new PersonBirthDate($model->birth_date?->format('Y-m-d')),
            new PersonBirthCityId($model->birth_city?->id ?? $model->birth_city_id),
            new PersonDocumentNumber($model->document_number),
            new PersonDocumentIssueCityId($model->document_issue_city?->id ?? $model->document_issue_city_id),
            new PersonDocumentIssueDate($model->document_issue_date?->format('Y-m-d')),
            new PersonCityId($model->city?->id ?? $model->city_id),
            new PersonAddress($model->address),
            new PersonNeighborhood($model->neighborhood),
            new PersonDialingCountryId($model->dialing_country?->id ?? $model->dialing_country_id),
            new PersonPhoneNumber($model->phone_number),
            new PersonPhoneNumberVerifiedAt($model->phone_number_verified_at?->format('Y-m-d H:i:s')),
            new PersonEmail($model->email),
            new PersonEmailVerifiedAt($model->email_verified_at?->format('Y-m-d H:i:s')),
            new PersonDocumentTypeId($model->document_type?->id ?? $model->document_type_id),
            new PersonSexId($model->sex?->id ?? $model->dialing_cousex_id),
            new PersonGenderId($model->gender?->id ?? $model->gender_id),
            new PersonBloodTypeId($model->blood_type?->id ?? $model->blood_type_id),
            new PersonMaritalStatusId($model->marital_status?->id ?? $model->marital_status_id),
            new PersonVacantId(),
            new PersonEntityId(),
            new PersonCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new PersonUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new PersonDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new PersonRelationships($model->birth_city, $model->document_issue_city, $model->city, $model->dialing_country, $model->document_type, $model->sex, $model->gender, $model->blood_type, $model->marital_status, $model->vacants, $model->entities, $model->file, $model->photo, $model->signature, null, $model->employee)
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

    private function mapToModelData(Person $person): array
    {
        $array = [];

        if ($person->firstName()->value()) {
            $array['first_name'] = $person->firstName()->value();
        }

        $array['second_name'] = $person->secondName()->value();

        if ($person->firstLastName()->value()) {
            $array['first_last_name'] = $person->firstLastName()->value();
        }

        $array['second_last_name'] = $person->secondLastName()->value();

        if ($person->birthDate()->value()) {
            $array['birth_date'] = $person->birthDate()->value();
        }

        if ($person->birthCityId()->value()) {
            $array['birth_city_id'] = $person->birthCityId()->value();
        }

        if ($person->documentNumber()->value()) {
            $array['document_number'] = $person->documentNumber()->value();
        }

        if ($person->documentIssueCityId()->value()) {
            $array['document_issue_city_id'] = $person->documentIssueCityId()->value();
        }

        if ($person->documentIssueDate()->value()) {
            $array['document_issue_date'] = $person->documentIssueDate()->value();
        }

        if ($person->cityId()->value()) {
            $array['city_id'] = $person->cityId()->value();
        }

        if ($person->address()->value()) {
            $array['address'] = $person->address()->value();
        }

        if ($person->neighborhood()->value()) {
            $array['neighborhood'] = $person->neighborhood()->value();
        }

        if ($person->dialingCountryId()->value()) {
            $array['dialing_country_id'] = $person->dialingCountryId()->value();
        }

        if ($person->phoneNumber()->value()) {
            $array['phone_number'] = $person->phoneNumber()->value();
        }

        if ($person->email()->value()) {
            $array['email'] = $person->email()->value();
        }

        if ($person->documentTypeId()->value()) {
            $array['document_type_id'] = $person->documentTypeId()->value();
        }

        if ($person->sexId()->value()) {
            $array['sex_id'] = $person->sexId()->value();
        }

        if ($person->genderId()->value()) {
            $array['gender_id'] = $person->genderId()->value();
        }

        if ($person->bloodTypeId()->value()) {
            $array['blood_type_id'] = $person->bloodTypeId()->value();
        }

        if ($person->maritalStatusId()->value()) {
            $array['marital_status_id'] = $person->maritalStatusId()->value();
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
            $array['keys']['file_type_id'] = $file->fileTypeId()->value();
        }

        if ($file->fileSubtypeId()->value()) {
            $array['keys']['file_subtype_id'] = $file->fileSubtypeId()->value();
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
