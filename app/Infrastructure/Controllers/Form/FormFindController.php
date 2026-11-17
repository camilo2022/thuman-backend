<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormFindUseCase;
use App\Domain\Form\Values\FormId;
use App\Http\Requests\Form\FormFindRequest;
use App\Http\Resources\Form\FormResource;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormFindController
{
    use ApiResponser;
    use ApiMessage;
    private $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function __invoke(FormFindRequest $request, int|null $formId = null)
    {
        try {
            $formId = new FormId($formId);
            $formFindUseCase = new FormFindUseCase($this->formRepository);
            $form = new FormResource($formFindUseCase->__invoke($request, $formId));
            return $this->successResponse(
                $form,
                'El formulario fue encontrado exitosamente.',
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
