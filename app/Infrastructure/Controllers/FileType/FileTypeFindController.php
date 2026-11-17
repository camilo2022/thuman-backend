<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FileType;

use App\Application\FileTypeUseCase\FileTypeFindUseCase;
use App\Domain\FileType\Values\FileTypeId;
use App\Http\Resources\FileType\FileTypeResource;
use App\Infrastructure\Repositories\FileTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FileTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $fileTypeRepository;

    public function __construct(FileTypeRepository $fileTypeRepository)
    {
        $this->fileTypeRepository = $fileTypeRepository;
    }

    public function __invoke(int $fileTypeId)
    {
        try {
            $fileTypeId = new FileTypeId($fileTypeId ?? null);
            $fileTypeFindUseCase = new FileTypeFindUseCase($this->fileTypeRepository);
            $fileType = new FileTypeResource($fileTypeFindUseCase->__invoke($fileTypeId));
            return $this->successResponse(
                $fileType,
                'El tipo de archivo fue encontrada exitosamente.',
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
