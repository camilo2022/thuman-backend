<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredSkill;

use App\Application\PositionUseCase\RequiredSkillUseCase\RequiredSkillFindUseCase;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Http\Resources\Position\RequiredSkill\RequiredSkillResource;
use App\Infrastructure\Repositories\RequiredSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredSkillFindController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredSkillRepository;

    public function __construct(RequiredSkillRepository $requiredSkillRepository)
    {
        $this->requiredSkillRepository = $requiredSkillRepository;
    }

    public function __invoke(int $requiredSkillId)
    {
        try {
            $requiredSkillId = new RequiredSkillId($requiredSkillId ?? null);
            $requiredSkillFindUseCase = new RequiredSkillFindUseCase($this->requiredSkillRepository);
            $requiredSkill = new RequiredSkillResource($requiredSkillFindUseCase->__invoke($requiredSkillId));
            return $this->successResponse(
                $requiredSkill,
                'La habilidad fue encontrado exitosamente.',
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
