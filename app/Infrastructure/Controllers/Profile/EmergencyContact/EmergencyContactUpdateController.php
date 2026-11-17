<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\EmergencyContact;

use App\Application\ProfileUseCase\EmergencyContactUseCase\EmergencyContactUpdateUseCase;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactUpdateRequest;
use App\Http\Resources\Profile\EmergencyContact\EmergencyContactResource;
use App\Infrastructure\Repositories\EmergencyContactRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EmergencyContactUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $emergencyContactRepository;

    public function __construct(EmergencyContactRepository $emergencyContactRepository)
    {
        $this->emergencyContactRepository = $emergencyContactRepository;
    }

    public function __invoke(EmergencyContactUpdateRequest $request, int $emergencyContactId)
    {
        Gate::authorize('updateEmergencyContact', $emergencyContactId);
        
        try {
            $emergencyContactId = new EmergencyContactId($emergencyContactId ?? null);
            $emergencyContactUpdateUseCase = new EmergencyContactUpdateUseCase($this->emergencyContactRepository);
            $emergencyContact = new EmergencyContactResource($emergencyContactUpdateUseCase->__invoke($request, $emergencyContactId));
            return $this->successResponse(
                $emergencyContact,
                'El contacto de emergencia fue actualizado exitosamente.',
                200
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
