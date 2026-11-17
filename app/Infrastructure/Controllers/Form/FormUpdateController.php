<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormUpdateUseCase;
use App\Domain\Form\Values\FormId;
use App\Http\Requests\Form\FormUpdateRequest;
use App\Http\Resources\Form\FormResource;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormUpdateController
{
    use ApiResponser;
    use ApiMessage;
    private $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function __invoke(FormUpdateRequest $request, int $formId)
    {
        try {
            $formId = new FormId($formId ?? null);
            $formUpdateUseCase = new FormUpdateUseCase($this->formRepository);
            $form = new FormResource($formUpdateUseCase->__invoke($request, $formId));
            return $this->successResponse(
                $form,
                'El formulario fue actualizado exitosamente.',
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
