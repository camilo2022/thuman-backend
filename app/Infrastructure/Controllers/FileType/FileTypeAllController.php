<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FileType;

use App\Application\FileTypeUseCase\FileTypeAllUseCase;
use App\Http\Requests\FileType\FileTypeAllRequest;
use App\Http\Resources\FileType\FileTypeCollection;
use App\Infrastructure\Repositories\FileTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FileTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $fileTypeRepository;

    public function __construct(FileTypeRepository $fileTypeRepository)
    {
        $this->fileTypeRepository = $fileTypeRepository;
    }

    public function __invoke(FileTypeAllRequest $request)
    {
        try {
            $fileTypeAllUseCase = new FileTypeAllUseCase($this->fileTypeRepository);
            $fileTypes = new FileTypeCollection($fileTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $fileTypes,
                'Los tipos de archivos consultados exitosamente.',
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
