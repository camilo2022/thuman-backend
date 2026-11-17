<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\CriminalRecord;

use App\Application\VacantUseCase\ApplicationUseCase\CriminalRecordUseCase\CriminalRecordFindUseCase;
use App\Domain\Vacant\Application\CriminalRecord\Values\CriminalRecordId;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordFindRequest;
use App\Http\Resources\Vacant\Application\CriminalRecord\CriminalRecordResource;
use App\Infrastructure\Repositories\CriminalRecordRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CriminalRecordFindController
{
    use ApiResponser;
    use ApiMessage;
    private $criminalRecordRepository;

    public function __construct(CriminalRecordRepository $criminalRecordRepository)
    {
        $this->criminalRecordRepository = $criminalRecordRepository;
    }

    public function __invoke(CriminalRecordFindRequest $request, int|null $criminalRecordId = null)
    {
        try {
            $criminalRecordId = new CriminalRecordId($criminalRecordId ?? null);
            $criminalRecordFindUseCase = new CriminalRecordFindUseCase($this->criminalRecordRepository);
            $criminalRecord = new CriminalRecordResource($criminalRecordFindUseCase->__invoke($request, $criminalRecordId));
            return $this->successResponse(
                $criminalRecord,
                'El registro de antecedentes criminales fue encontrada exitosamente.',
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
