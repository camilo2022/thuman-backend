<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FileSubtype;

use App\Application\FileSubtypeUseCase\FileSubtypeFindUseCase;
use App\Domain\FileSubtype\Values\FileSubtypeId;
use App\Http\Resources\FileSubtype\FileSubtypeResource;
use App\Infrastructure\Repositories\FileSubtypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FileSubtypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $fileSubtypeRepository;

    public function __construct(FileSubtypeRepository $fileSubtypeRepository)
    {
        $this->fileSubtypeRepository = $fileSubtypeRepository;
    }

    public function __invoke(int $fileSubtypeId)
    {
        try {
            $fileSubtypeId = new FileSubtypeId($fileSubtypeId ?? null);
            $fileSubtypeFindUseCase = new FileSubtypeFindUseCase($this->fileSubtypeRepository);
            $fileSubtype = new FileSubtypeResource($fileSubtypeFindUseCase->__invoke($fileSubtypeId));
            return $this->successResponse(
                $fileSubtype,
                'El subtipo de archivo fue encontrada exitosamente.',
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
