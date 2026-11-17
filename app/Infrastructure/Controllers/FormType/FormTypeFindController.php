<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\FormType;

use App\Application\FormTypeUseCase\FormTypeFindUseCase;
use App\Domain\FormType\Values\FormTypeId;
use App\Http\Resources\FormType\FormTypeResource;
use App\Infrastructure\Repositories\FormTypeRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormTypeFindController
{
    use ApiResponser;
    use ApiMessage;
    private $formTypeRepository;

    public function __construct(FormTypeRepository $formTypeRepository)
    {
        $this->formTypeRepository = $formTypeRepository;
    }

    public function __invoke(int $formTypeId)
    {
        try {
            $formTypeId = new FormTypeId($formTypeId ?? null);
            $formTypeFindUseCase = new FormTypeFindUseCase($this->formTypeRepository);
            $formType = new FormTypeResource($formTypeFindUseCase->__invoke($formTypeId));
            return $this->successResponse(
                $formType,
                'El tipo de formulario fue encontrada exitosamente.',
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
