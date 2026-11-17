<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormStoreUseCase;
use App\Http\Requests\Form\FormStoreRequest;
use App\Http\Resources\Form\FormResource;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormStoreController
{
    use ApiResponser;
    use ApiMessage;
    private $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function __invoke(FormStoreRequest $request)
    {
        try {
            $formStoreUseCase = new FormStoreUseCase($this->formRepository);
            $form = new FormResource($formStoreUseCase->__invoke($request));
            return $this->successResponse(
                $form,
                'El formulario registrado exitosamente.',
                201
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
