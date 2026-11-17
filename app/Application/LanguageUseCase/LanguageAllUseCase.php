<?php

declare(strict_types=1);

namespace App\Application\LanguageUseCase;

use App\Domain\Language\Contracts\LanguageContract;
use App\Http\Requests\Language\LanguageAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class LanguageAllUseCase
{
    private $languageContract;

    public function __construct(LanguageContract $languageContract)
    {
        $this->languageContract = $languageContract;
    }

    public function __invoke(LanguageAllRequest $request): LengthAwarePaginator
    {
        return $this->languageContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
