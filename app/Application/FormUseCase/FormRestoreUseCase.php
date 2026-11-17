<?php

declare(strict_types=1);

namespace App\Application\FormUseCase;

use App\Domain\Form\Form;
use App\Domain\Form\Contracts\FormContract;
use App\Domain\Form\Values\FormId;

final class FormRestoreUseCase
{
    private $formContract;

    public function __construct(FormContract $formContract)
    {
        $this->formContract = $formContract;
    }

    public function __invoke(FormId $formId): ?Form
    {
        return $this->formContract->restore($formId);
    }
}
