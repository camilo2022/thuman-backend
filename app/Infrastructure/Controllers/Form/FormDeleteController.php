<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormDeleteUseCase;
use App\Domain\Form\Values\FormId;
use App\Http\Resources\Form\FormResource;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class FormDeleteController
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
            $formDeleteUseCase = new FormDeleteUseCase($this->formRepository);
            $form = new FormResource($formDeleteUseCase->__invoke($formId));
            return $this->successResponse(
                $form,
                'El formulario fue eliminado exitosamente.',
                204
            );
        } catch (HttpException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage(),
                    'status' => $e->getStatusCode()
                ],
                $e->getStatusCode()
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
