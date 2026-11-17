<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\EmergencyContact;

use App\Application\ProfileUseCase\EmergencyContactUseCase\EmergencyContactStoreUseCase;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactStoreRequest;
use App\Http\Resources\Profile\EmergencyContact\EmergencyContactResource;
use App\Infrastructure\Repositories\EmergencyContactRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EmergencyContactStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $emergencyContactRepository;

    public function __construct(EmergencyContactRepository $emergencyContactRepository)
    {
        $this->emergencyContactRepository = $emergencyContactRepository;
    }

    public function __invoke(EmergencyContactStoreRequest $request)
    {
        Gate::authorize('storeEmergencyContact', $request->input('profile_id'));

        try {
            $emergencyContactStoreUseCase = new EmergencyContactStoreUseCase($this->emergencyContactRepository);
            $emergencyContact = new EmergencyContactResource($emergencyContactStoreUseCase->__invoke($request));
            return $this->successResponse(
                $emergencyContact,
                'El contacto de emergencia fue registrado exitosamente.',
                201
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
