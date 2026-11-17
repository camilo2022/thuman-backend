<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Position\RequiredSkill;

use App\Application\PositionUseCase\RequiredSkillUseCase\RequiredSkillAllUseCase;
use App\Domain\Position\Values\PositionId;
use App\Http\Requests\Position\RequiredSkill\RequiredSkillAllRequest;
use App\Http\Resources\Position\RequiredSkill\RequiredSkillCollection;
use App\Infrastructure\Repositories\RequiredSkillRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class RequiredSkillAllController
{
    use ApiResponser;
    use ApiMessage;
    private $requiredSkillRepository;

    public function __construct(RequiredSkillRepository $requiredSkillRepository)
    {
        $this->requiredSkillRepository = $requiredSkillRepository;
    }

    public function __invoke(RequiredSkillAllRequest $request, int $positionId)
    {
        try {
            $positionId = new PositionId($positionId ?? null);
            $requiredSkillAllUseCase = new RequiredSkillAllUseCase($this->requiredSkillRepository);
            $requiredSkills = new RequiredSkillCollection($requiredSkillAllUseCase->__invoke($request, $positionId));
            return $this->successResponse(
                $requiredSkills,
                'Las habilidades fueron consultados exitosamente.',
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
