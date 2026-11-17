<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormRestoreUseCase;
use App\Domain\Form\Values\FormId;
use App\Http\Resources\Form\FormResource;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function __invoke(int $formId)
    {
        try {
            $formId = new FormId($formId);
            $formRestoreUseCase = new FormRestoreUseCase($this->formRepository);
            $form = new FormResource($formRestoreUseCase->__invoke($formId));
            return $this->successResponse(
                $form,
                'El formulario fue restaurado exitosamente.',
                204
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
