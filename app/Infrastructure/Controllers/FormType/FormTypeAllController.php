<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FormType;

use App\Application\FormTypeUseCase\FormTypeAllUseCase;
use App\Http\Requests\FormType\FormTypeAllRequest;
use App\Http\Resources\FormType\FormTypeCollection;
use App\Infrastructure\Repositories\FormTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormTypeAllController
{
    use ApiResponser;
    use ApiMessage;
    private $formTypeRepository;

    public function __construct(FormTypeRepository $formTypeRepository)
    {
        $this->formTypeRepository = $formTypeRepository;
    }

    public function __invoke(FormTypeAllRequest $request)
    {
        try {
            $formTypeAllUseCase = new FormTypeAllUseCase($this->formTypeRepository);
            $formTypes = new FormTypeCollection($formTypeAllUseCase->__invoke($request));
            return $this->successResponse(
                $formTypes,
                'Los tipos de formularios consultados exitosamente.',
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
