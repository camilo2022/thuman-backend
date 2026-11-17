<?php

declare(strict_types=1);

namespace App\Application\LanguageLevelUseCase;

use App\Domain\LanguageLevel\LanguageLevel;
use App\Domain\LanguageLevel\Contracts\LanguageLevelContract;
use App\Domain\LanguageLevel\Values\LanguageLevelId;

final class LanguageLevelFindUseCase
{
    private $languageLevelContract;

    public function __construct(LanguageLevelContract $languageLevelContract)
    {
        $this->languageLevelContract = $languageLevelContract;
    }

    public function __invoke(LanguageLevelId $languageLevelId): ?LanguageLevel
    {
        return $this->languageLevelContract->find($languageLevelId);
    }
}
