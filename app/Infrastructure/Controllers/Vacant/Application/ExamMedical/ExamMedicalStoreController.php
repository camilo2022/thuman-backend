<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamMedical;

use App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase\ExamMedicalStoreUseCase;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalStoreRequest;
use App\Http\Resources\Vacant\Application\ExamMedical\ExamMedicalResource;
use App\Infrastructure\Repositories\ExamMedicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamMedicalStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $examMedicalRepository;

    public function __construct(ExamMedicalRepository $examMedicalRepository)
    {
        $this->examMedicalRepository = $examMedicalRepository;
    }

    public function __invoke(ExamMedicalStoreRequest $request)
    {
        try {
            $examMedicalStoreUseCase = new ExamMedicalStoreUseCase($this->examMedicalRepository);
            $examMedical = new ExamMedicalResource($examMedicalStoreUseCase->__invoke($request));
            return $this->successResponse(
                $examMedical,
                'El agendamiento del examen médico fue registrado exitosamente.',
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
