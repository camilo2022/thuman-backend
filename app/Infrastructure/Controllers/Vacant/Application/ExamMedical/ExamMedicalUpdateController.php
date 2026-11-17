<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamMedical;

use App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase\ExamMedicalUpdateUseCase;
use App\Domain\Vacant\Application\ExamMedical\Values\ExamMedicalId;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalUpdateRequest;
use App\Http\Resources\Vacant\Application\ExamMedical\ExamMedicalResource;
use App\Infrastructure\Repositories\ExamMedicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamMedicalUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $examMedicalRepository;

    public function __construct(ExamMedicalRepository $examMedicalRepository)
    {
        $this->examMedicalRepository = $examMedicalRepository;
    }

    public function __invoke(ExamMedicalUpdateRequest $request, int $examMedicalId)
    {
        try {
            $examMedicalId = new ExamMedicalId($examMedicalId ?? null);
            $examMedicalUpdateUseCase = new ExamMedicalUpdateUseCase($this->examMedicalRepository);
            $examMedical = new ExamMedicalResource($examMedicalUpdateUseCase->__invoke($request, $examMedicalId));
            return $this->successResponse(
                $examMedical,
                'El agendamiento del examen médico fue actualizado exitosamente.',
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
