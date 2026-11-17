<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase;

use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class TemplateAllUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateAllRequest $request, EntityId $entityId): LengthAwarePaginator
    {
        return $this->templateContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $entityId, $request);
    }
}
