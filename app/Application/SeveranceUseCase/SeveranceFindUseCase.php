<?php

declare(strict_types=1);

namespace App\Application\SeveranceUseCase;

use App\Domain\Severance\Severance;
use App\Domain\Severance\Contracts\SeveranceContract;
use App\Domain\Severance\Values\SeveranceId;

final class SeveranceFindUseCase
{
    private $severanceContract;

    public function __construct(SeveranceContract $severanceContract)
    {
        $this->severanceContract = $severanceContract;
    }

    public function __invoke(SeveranceId $severanceId): ?Severance
    {
        return $this->severanceContract->find($severanceId);
    }
}
