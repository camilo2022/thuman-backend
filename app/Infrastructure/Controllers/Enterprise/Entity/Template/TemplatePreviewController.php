<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplatePreviewUseCase;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplatePreviewController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function __invoke(int $templateId)
    {
        Gate::authorize('previewTemplate');
        
        try {
            $templateId = new TemplateId($templateId ?? null);
            $templatePreviewUseCase = new TemplatePreviewUseCase($this->templateRepository);
            $template = $templatePreviewUseCase->__invoke($templateId);
            return $this->successResponse(
                $template,
                'La plantilla fue configurada exitosamente.',
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
