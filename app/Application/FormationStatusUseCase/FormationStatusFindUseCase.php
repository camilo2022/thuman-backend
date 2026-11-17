<?php

declare(strict_types=1);

namespace App\Application\FormationStatusUseCase;

use App\Domain\FormationStatus\FormationStatus;
use App\Domain\FormationStatus\Contracts\FormationStatusContract;
use App\Domain\FormationStatus\Values\FormationStatusId;

final class FormationStatusFindUseCase
{
    private $formationStatusContract;

    public function __construct(FormationStatusContract $formationStatusContract)
    {
        $this->formationStatusContract = $formationStatusContract;
    }

    public function __invoke(FormationStatusId $formationStatusId): ?FormationStatus
    {
        return $this->formationStatusContract->find($formationStatusId);
    }
}
