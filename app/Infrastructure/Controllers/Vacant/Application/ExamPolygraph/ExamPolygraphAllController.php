<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamPolygraph;

use App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase\ExamPolygraphAllUseCase;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphAllRequest;
use App\Http\Resources\Vacant\Application\ExamPolygraph\ExamPolygraphCollection;
use App\Infrastructure\Repositories\ExamPolygraphRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamPolygraphAllController
{
    use ApiResponser;
    use ApiMessage;
    private $examPolygraphRepository;

    public function __construct(ExamPolygraphRepository $examPolygraphRepository)
    {
        $this->examPolygraphRepository = $examPolygraphRepository;
    }

    public function __invoke(ExamPolygraphAllRequest $request)
    {
        try {
            $examPolygraphAllUseCase = new ExamPolygraphAllUseCase($this->examPolygraphRepository);
            $examPolygraphs = new ExamPolygraphCollection($examPolygraphAllUseCase->__invoke($request));
            return $this->successResponse(
                $examPolygraphs,
                'Los agendamientos/resultados de los examenes polígrafo consultadas exitosamente.',
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
