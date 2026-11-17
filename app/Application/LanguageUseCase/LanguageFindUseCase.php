<?php

declare(strict_types=1);

namespace App\Application\LanguageUseCase;

use App\Domain\Language\Language;
use App\Domain\Language\Contracts\LanguageContract;
use App\Domain\Language\Values\LanguageId;

final class LanguageFindUseCase
{
    private $languageContract;

    public function __construct(LanguageContract $languageContract)
    {
        $this->languageContract = $languageContract;
    }

    public function __invoke(LanguageId $languageId): ?Language
    {
        return $this->languageContract->find($languageId);
    }
}
