<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Template\Contracts;

use App\Domain\Enterprise\Entity\Template\Template;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateAllRequest;
use App\Http\Requests\Enterprise\Entity\Template\TemplateFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface TemplateContract
{
    public function all(string|null $search, int|null $perPage, EntityId $entityId, TemplateAllRequest $request): LengthAwarePaginator;

    public function find(TemplateFindRequest $request, TemplateId $templateId): ?Template;

    public function store(Template $template): Template;

    public function update(Template $template, TemplateId $templateId): Template;

    public function delete(TemplateId $templateId): Template;

    public function restore(TemplateId $templateId): Template;

    public function preview(TemplateId $templateId);
}
