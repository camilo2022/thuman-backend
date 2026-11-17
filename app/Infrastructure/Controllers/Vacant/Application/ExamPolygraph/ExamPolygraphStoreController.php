<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamPolygraph;

use App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase\ExamPolygraphStoreUseCase;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphStoreRequest;
use App\Http\Resources\Vacant\Application\ExamPolygraph\ExamPolygraphResource;
use App\Infrastructure\Repositories\ExamPolygraphRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamPolygraphStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $examPolygraphRepository;

    public function __construct(ExamPolygraphRepository $examPolygraphRepository)
    {
        $this->examPolygraphRepository = $examPolygraphRepository;
    }

    public function __invoke(ExamPolygraphStoreRequest $request)
    {
        try {
            $examPolygraphStoreUseCase = new ExamPolygraphStoreUseCase($this->examPolygraphRepository);
            $examPolygraph = new ExamPolygraphResource($examPolygraphStoreUseCase->__invoke($request));
            return $this->successResponse(
                $examPolygraph,
                'El agendamiento del examen polígrafo fue registrada exitosamente.',
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
