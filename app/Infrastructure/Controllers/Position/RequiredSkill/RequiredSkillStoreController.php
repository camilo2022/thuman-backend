<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredSkill;

use App\Application\PositionUseCase\RequiredSkillUseCase\RequiredSkillStoreUseCase;
use App\Http\Requests\Position\RequiredSkill\RequiredSkillStoreRequest;
use App\Http\Resources\Position\RequiredSkill\RequiredSkillResource;
use App\Infrastructure\Repositories\RequiredSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredSkillStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredSkillRepository;

    public function __construct(RequiredSkillRepository $requiredSkillRepository)
    {
        $this->requiredSkillRepository = $requiredSkillRepository;
    }

    public function __invoke(RequiredSkillStoreRequest $request)
    {
        try {
            $requiredSkillStoreUseCase = new RequiredSkillStoreUseCase($this->requiredSkillRepository);
            $requiredSkill = new RequiredSkillResource($requiredSkillStoreUseCase->__invoke($request));
            return $this->successResponse(
                $requiredSkill,
                'La habilidad fue registrado exitosamente.',
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
