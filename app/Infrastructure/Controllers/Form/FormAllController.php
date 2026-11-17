<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Form;

use App\Application\FormUseCase\FormAllUseCase;
use App\Http\Requests\Form\FormAllRequest;
use App\Http\Resources\Form\FormCollection;
use App\Infrastructure\Repositories\FormRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use OpenApi\Annotations as OA;

final class FormAllController
{
    use ApiResponser;
    use ApiMessage;
    private $formRepository;

    public function __construct(FormRepository $formRepository)
    {
        $this->formRepository = $formRepository;
    }

    public function __invoke(FormAllRequest $request)
    {
        try {
            $formAllUseCase = new FormAllUseCase($this->formRepository);
            $forms = new FormCollection($formAllUseCase->__invoke($request));
            return $this->successResponse(
                $forms,
                'Los formularios consultados exitosamente.',
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
