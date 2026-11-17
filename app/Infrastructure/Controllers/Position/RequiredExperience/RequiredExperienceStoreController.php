<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredExperience;

use App\Application\PositionUseCase\RequiredExperienceUseCase\RequiredExperienceStoreUseCase;
use App\Http\Requests\Position\RequiredExperience\RequiredExperienceStoreRequest;
use App\Http\Resources\Position\RequiredExperience\RequiredExperienceResource;
use App\Infrastructure\Repositories\RequiredExperienceRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredExperienceStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredExperienceRepository;

    public function __construct(RequiredExperienceRepository $requiredExperienceRepository)
    {
        $this->requiredExperienceRepository = $requiredExperienceRepository;
    }

    public function __invoke(RequiredExperienceStoreRequest $request)
    {
        try {
            $requiredExperienceStoreUseCase = new RequiredExperienceStoreUseCase($this->requiredExperienceRepository);
            $requiredExperience = new RequiredExperienceResource($requiredExperienceStoreUseCase->__invoke($request));
            return $this->successResponse(
                $requiredExperience,
                'La experiencia requerida fue registrado exitosamente.',
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
