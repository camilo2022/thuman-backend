<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredSkill;

use App\Application\PositionUseCase\RequiredSkillUseCase\RequiredSkillDeleteUseCase;
use App\Domain\Position\RequiredSkill\Values\RequiredSkillId;
use App\Infrastructure\Repositories\RequiredSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredSkillDeleteController
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
            $requiredSkillId = new RequiredSkillId($requiredSkillId);
            $requiredSkillDeleteUseCase = new RequiredSkillDeleteUseCase($this->requiredSkillRepository);
            $requiredSkillDeleteUseCase->__invoke($requiredSkillId);
            return $this->successResponse(
                '',
                'La habilidad fue inactivada exitosamente.',
                204
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
