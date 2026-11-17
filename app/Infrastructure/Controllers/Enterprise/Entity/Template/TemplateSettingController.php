<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity\Template;

use App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase\TemplateSettingUseCase;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateSettingRequest;
use App\Http\Resources\Enterprise\Entity\Template\TemplateResource;
use App\Infrastructure\Repositories\TemplateRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class TemplateSettingController
{
    use ApiResponser;
    use ApiMessage;
    private $templateRepository;

    public function __construct(TemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }

    public function __invoke(TemplateSettingRequest $request, int $templateId)
    {
        Gate::authorize('settingTemplate');
        
        try {
            $templateId = new TemplateId($templateId ?? null);
            $templateSettingUseCase = new TemplateSettingUseCase($this->templateRepository);
            $template = new TemplateResource($templateSettingUseCase->__invoke($request, $templateId));
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
