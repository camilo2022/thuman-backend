<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase;

use App\Domain\Enterprise\Entity\Template\Template;
use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateFindRequest;

final class TemplateFindUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateFindRequest $request, TemplateId $templateId): ?Template
    {
        return $this->templateContract->find($request, $templateId);
    }
}
