<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamMedical;

use App\Application\VacantUseCase\ApplicationUseCase\ExamMedicalUseCase\ExamMedicalAllUseCase;
use App\Http\Requests\Vacant\Application\ExamMedical\ExamMedicalAllRequest;
use App\Http\Resources\Vacant\Application\ExamMedical\ExamMedicalCollection;
use App\Infrastructure\Repositories\ExamMedicalRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamMedicalAllController
{
    use ApiResponser;
    use ApiMessage;
    private $examMedicalRepository;

    public function __construct(ExamMedicalRepository $examMedicalRepository)
    {
        $this->examMedicalRepository = $examMedicalRepository;
    }

    public function __invoke(ExamMedicalAllRequest $request)
    {
        try {
            $examMedicalAllUseCase = new ExamMedicalAllUseCase($this->examMedicalRepository);
            $examMedicals = new ExamMedicalCollection($examMedicalAllUseCase->__invoke($request));
            return $this->successResponse(
                $examMedicals,
                'Los agendamientos/resultados de los examenes médicos consultadas exitosamente.',
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
