<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\ExamPolygraph;

use App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase\ExamPolygraphUpdateUseCase;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphUpdateRequest;
use App\Http\Resources\Vacant\Application\ExamPolygraph\ExamPolygraphResource;
use App\Infrastructure\Repositories\ExamPolygraphRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class ExamPolygraphUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $examPolygraphRepository;

    public function __construct(ExamPolygraphRepository $examPolygraphRepository)
    {
        $this->examPolygraphRepository = $examPolygraphRepository;
    }

    public function __invoke(ExamPolygraphUpdateRequest $request, int $examPolygraphId)
    {
        try {
            $examPolygraphId = new ExamPolygraphId($examPolygraphId ?? null);
            $examPolygraphUpdateUseCase = new ExamPolygraphUpdateUseCase($this->examPolygraphRepository);
            $examPolygraph = new ExamPolygraphResource($examPolygraphUpdateUseCase->__invoke($request, $examPolygraphId));
            return $this->successResponse(
                $examPolygraph,
                'El agendamiento del examen polígrafo fue actualizado exitosamente.',
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
