<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Profile\EmergencyContact;

use App\Application\ProfileUseCase\EmergencyContactUseCase\EmergencyContactFindUseCase;
use App\Domain\Profile\EmergencyContact\Values\EmergencyContactId;
use App\Http\Requests\Profile\EmergencyContact\EmergencyContactFindRequest;
use App\Http\Resources\Profile\EmergencyContact\EmergencyContactResource;
use App\Infrastructure\Repositories\EmergencyContactRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EmergencyContactFindController
{
    use ApiResponser;
    use ApiMessage;
    private $emergencyContactRepository;

    public function __construct(EmergencyContactRepository $emergencyContactRepository)
    {
        $this->emergencyContactRepository = $emergencyContactRepository;
    }

    public function __invoke(EmergencyContactFindRequest $request, int|null $emergencyContactId = null)
    {
        Gate::authorize('findEmergencyContact', [$emergencyContactId, (object) [
            'profile_id' => $request->input('profile_id')
        ]]);
        
        try {
            $emergencyContactId = new EmergencyContactId($emergencyContactId);
            $emergencyContactFindUseCase = new EmergencyContactFindUseCase($this->emergencyContactRepository);
            $emergencyContact = new EmergencyContactResource($emergencyContactFindUseCase->__invoke($request, $emergencyContactId));
            return $this->successResponse(
                $emergencyContact,
                'El contacto de emergencia fue encontrado exitosamente.',
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
