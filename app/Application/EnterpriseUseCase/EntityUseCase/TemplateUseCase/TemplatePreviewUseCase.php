<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase;

use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;

final class TemplatePreviewUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateId $templateId)
    {
        return $this->templateContract->preview($templateId);
    }
}
