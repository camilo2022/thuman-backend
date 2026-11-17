<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase;

use App\Domain\Enterprise\Entity\Template\Template;
use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;

final class TemplateDeleteUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateId $templateId): ?Template
    {
        return $this->templateContract->delete($templateId);
    }
}
