<?php

declare(strict_types=1);

namespace App\Application\GenderUseCase;

use App\Domain\Gender\Gender;
use App\Domain\Gender\Contracts\GenderContract;
use App\Domain\Gender\Values\GenderId;

final class GenderFindUseCase
{
    private $genderContract;

    public function __construct(GenderContract $genderContract)
    {
        $this->genderContract = $genderContract;
    }

    public function __invoke(GenderId $genderId): ?Gender
    {
        return $this->genderContract->find($genderId);
    }
}
