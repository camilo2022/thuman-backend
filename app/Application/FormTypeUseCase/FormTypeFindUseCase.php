<?php

declare(strict_types=1);

namespace App\Application\FormTypeUseCase;

use App\Domain\FormType\FormType;
use App\Domain\FormType\Contracts\FormTypeContract;
use App\Domain\FormType\Values\FormTypeId;

final class FormTypeFindUseCase
{
    private $formTypeContract;

    public function __construct(FormTypeContract $formTypeContract)
    {
        $this->formTypeContract = $formTypeContract;
    }

    public function __invoke(FormTypeId $formTypeId): ?FormType
    {
        return $this->formTypeContract->find($formTypeId);
    }
}
