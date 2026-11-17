<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Vacant\Application\CriminalRecord;

use App\Application\VacantUseCase\ApplicationUseCase\CriminalRecordUseCase\CriminalRecordStoreUseCase;
use App\Http\Requests\Vacant\Application\CriminalRecord\CriminalRecordStoreRequest;
use App\Http\Resources\Vacant\Application\CriminalRecord\CriminalRecordResource;
use App\Infrastructure\Repositories\CriminalRecordRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class CriminalRecordStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $criminalRecordRepository;

    public function __construct(CriminalRecordRepository $criminalRecordRepository)
    {
        $this->criminalRecordRepository = $criminalRecordRepository;
    }

    public function __invoke(CriminalRecordStoreRequest $request)
    {
        try {
            $criminalRecordStoreUseCase = new CriminalRecordStoreUseCase($this->criminalRecordRepository);
            $criminalRecord = new CriminalRecordResource($criminalRecordStoreUseCase->__invoke($request));
            return $this->successResponse(
                $criminalRecord,
                'El registro de antecedentes criminales fue registrada exitosamente.',
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
