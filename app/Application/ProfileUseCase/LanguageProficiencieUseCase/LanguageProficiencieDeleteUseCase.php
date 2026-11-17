<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\LanguageProficiencieUseCase;

use App\Domain\Profile\LanguageProficiencie\LanguageProficiencie;
use App\Domain\Profile\LanguageProficiencie\Contracts\LanguageProficiencieContract;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;

final class LanguageProficiencieDeleteUseCase
{
    private $languageProficiencieContract;

    public function __construct(LanguageProficiencieContract $languageProficiencieContract)
    {
        $this->languageProficiencieContract = $languageProficiencieContract;
    }

    public function __invoke(LanguageProficiencieId $languageProficiencieId): ?LanguageProficiencie
    {
        return $this->languageProficiencieContract->delete($languageProficiencieId);
    }
}
