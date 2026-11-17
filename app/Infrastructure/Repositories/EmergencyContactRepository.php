<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Profile\EmergencyContact\Contracts\EmergencyContactContract;
use App\Models\EmergencyContact as EmergencyContactModel;
use App\Domain\Profile\EmergencyContact\EmergencyContact;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactCreatedAt;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactDialingCountryId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactFirstName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactPhoneNumber;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactProfileId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactRelationshipId;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactRelationships;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondLastName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactSecondName;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactUpdatedAt;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactFindRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class EmergencyContactRepository implements EmergencyContactContract
{
    private $emergencyContactModel;

    public function __construct()
    {
        $this->emergencyContactModel = new EmergencyContactModel;
    }

    public function find(EmergencyContactFindRequest $request, EmergencyContactId $emergencyContactId): ?EmergencyContact
    {
        try {
            $model = $this->emergencyContactModel
                ->with(['profile', 'relationship'])
                ->when($emergencyContactId->value(), function ($query) use ($emergencyContactId) {
                    $query->where('id', $emergencyContactId->value());
                })
                ->when(!$emergencyContactId->value(), function ($query) use ($request) {
                    $query->when($request->filled('profile_id'), function ($query) use ($request) {
                        $query->where('profile_id', $request->integer('profile_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contacto de emergecia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el contacto de emergecia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el contacto de emergecia: ' . $e->getMessage());
        }
    }

    public function store(EmergencyContact $emergencyContact): EmergencyContact
    {
        try {
            $model = $this->emergencyContactModel
                ->create($this->mapToModelData($emergencyContact));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el contacto de emergecia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el contacto de emergecia: ' . $e->getMessage());
        }
    }

    public function update(EmergencyContact $emergencyContact, EmergencyContactId $emergencyContactId): EmergencyContact
    {
        try {
            $model = tap($this->emergencyContactModel->findOrFail($emergencyContactId->value()))
                ->update($this->mapToModelData($emergencyContact))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contacto de emergecia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el contacto de emergecia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el contacto de emergecia: ' . $e->getMessage());
        }
    }

    public function delete(EmergencyContactId $emergencyContactId): void
    {
        try {
            $this->emergencyContactModel->findOrFail($emergencyContactId->value())
                ->delete();
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contacto de emergecia no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar el contacto de emergecia: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar el contacto de emergecia: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): EmergencyContact
    {
        return new EmergencyContact(
            new EmergencyContactId($model->id),
            new EmergencyContactProfileId($model->profile?->id ?? $model->profile_id),
            new EmergencyContactFirstName($model->first_name),
            new EmergencyContactSecondName($model->second_name),
            new EmergencyContactFirstLastName($model->first_last_name),
            new EmergencyContactSecondLastName($model->second_last_name),
            new EmergencyContactRelationshipId($model->relationship?->id ?? $model->relationship_id),
            new EmergencyContactDialingCountryId($model->dialing_country?->id ?? $model->dialing_country_id),
            new EmergencyContactPhoneNumber($model->phone_number),
            new EmergencyContactCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new EmergencyContactUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new EmergencyContactRelationships($model->profile, $model->relationship)
        );
    }

    private function mapToModelData(EmergencyContact $emergencyContact): array
    {
        $array = [];

        if ($emergencyContact->profileId()->value()) {
            $array['profile_id'] = $emergencyContact->profileId()->value();
        }

        if ($emergencyContact->firstName()->value()) {
            $array['first_name'] = $emergencyContact->firstName()->value();
        }

        if ($emergencyContact->secondName()->value()) {
            $array['second_name'] = $emergencyContact->secondName()->value();
        }

        if ($emergencyContact->firstLastName()->value()) {
            $array['first_last_name'] = $emergencyContact->firstLastName()->value();
        }

        if ($emergencyContact->secondLastName()->value()) {
            $array['second_last_name'] = $emergencyContact->secondLastName()->value();
        }

        if ($emergencyContact->relationshipId()->value()) {
            $array['relationship_id'] = $emergencyContact->relationshipId()->value();
        }

        if ($emergencyContact->dialingCountryId()->value()) {
            $array['dialing_country_id'] = $emergencyContact->dialingCountryId()->value();
        }

        if ($emergencyContact->phoneNumber()->value()) {
            $array['phone_number'] = $emergencyContact->phoneNumber()->value();
        }

        return $array;
    }
}
