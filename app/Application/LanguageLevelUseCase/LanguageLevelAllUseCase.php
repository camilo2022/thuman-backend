<?php

declare(strict_types=1);

namespace App\Application\LanguageLevelUseCase;

use App\Domain\LanguageLevel\Contracts\LanguageLevelContract;
use App\Http\Requests\LanguageLevel\LanguageLevelAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class LanguageLevelAllUseCase
{
    private $languageLevelContract;

    public function __construct(LanguageLevelContract $languageLevelContract)
    {
        $this->languageLevelContract = $languageLevelContract;
    }

    public function __invoke(LanguageLevelAllRequest $request): LengthAwarePaginator
    {
        return $this->languageLevelContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
