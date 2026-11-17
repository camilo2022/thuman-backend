<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\LanguageProficiencieUseCase;

use App\Domain\Profile\LanguageProficiencie\Contracts\LanguageProficiencieContract;
use App\Domain\Profile\Values\ProfileId;
use App\Http\Requests\Profile\LanguageProficiencie\LanguageProficiencieAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class LanguageProficiencieAllUseCase
{
    private $languageProficiencieContract;

    public function __construct(LanguageProficiencieContract $languageProficiencieContract)
    {
        $this->languageProficiencieContract = $languageProficiencieContract;
    }

    public function __invoke(LanguageProficiencieAllRequest $request, ProfileId $profileId): LengthAwarePaginator
    {
        return $this->languageProficiencieContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $profileId);
    }
}
