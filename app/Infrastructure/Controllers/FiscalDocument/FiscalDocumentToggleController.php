<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FiscalDocument;

use App\Application\FiscalDocumentUseCase\FiscalDocumentToggleUseCase;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;
use App\Http\Requests\FiscalDocument\FiscalDocumentToggleRequest;
use App\Http\Resources\FiscalDocument\FiscalDocumentResource;
use App\Infrastructure\Repositories\FiscalDocumentRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FiscalDocumentToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $fiscalDocumentRepository;

    public function __construct(FiscalDocumentRepository $fiscalDocumentRepository)
    {
        $this->fiscalDocumentRepository = $fiscalDocumentRepository;
    }

    public function __invoke(FiscalDocumentToggleRequest $request, int $fiscalDocumentId)
    {
        try {
            $fiscalDocumentId = new FiscalDocumentId($fiscalDocumentId ?? null);
            $fiscalDocumentToggleUseCase = new FiscalDocumentToggleUseCase($this->fiscalDocumentRepository);
            $fiscalDocument = new FiscalDocumentResource($fiscalDocumentToggleUseCase->__invoke($request, $fiscalDocumentId));
            return $this->successResponse(
                $fiscalDocument,
                'El país fue asignado/removido al documento fiscal exitosamente.',
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
