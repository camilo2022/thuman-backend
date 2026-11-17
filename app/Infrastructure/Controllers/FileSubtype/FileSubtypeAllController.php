<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FileSubtype;

use App\Application\FileSubtypeUseCase\FileSubtypeAllUseCase;
use App\Http\Requests\FileSubtype\FileSubtypeAllRequest;
use App\Http\Resources\FileSubtype\FileSubtypeCollection;
use App\Infrastructure\Repositories\FileSubtypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FileSubtypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $fileSubtypeRepository;

    public function __construct(FileSubtypeRepository $fileSubtypeRepository)
    {
        $this->fileSubtypeRepository = $fileSubtypeRepository;
    }

    public function __invoke(FileSubtypeAllRequest $request)
    {
        try {
            $fileSubtypeAllUseCase = new FileSubtypeAllUseCase($this->fileSubtypeRepository);
            $fileSubtypes = new FileSubtypeCollection($fileSubtypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $fileSubtypes,
                'Los subtipos de archivos consultados exitosamente.',
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
