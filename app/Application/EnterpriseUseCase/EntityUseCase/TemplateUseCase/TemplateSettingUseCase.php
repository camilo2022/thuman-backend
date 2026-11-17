<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\TemplateUseCase;

use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Domain\Enterprise\Entity\Template\Template;
use App\Domain\Enterprise\Entity\Template\Values\TemplateExtension;
use App\Domain\Enterprise\Entity\Template\Values\TemplateFileSubtypeId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateFileTypeId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateMetadata;
use App\Domain\Enterprise\Entity\Template\Values\TemplateMime;
use App\Domain\Enterprise\Entity\Template\Values\TemplateModelId;
use App\Domain\Enterprise\Entity\Template\Values\TemplateModelType;
use App\Domain\Enterprise\Entity\Template\Values\TemplateName;
use App\Domain\Enterprise\Entity\Template\Values\TemplatePath;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSettings;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSize;
use App\Http\Requests\Enterprise\Entity\Template\TemplateSettingRequest;
use App\Models\Entity as EntityModel;
use Illuminate\Support\Facades\Storage;

final class TemplateSettingUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateSettingRequest $request, TemplateId $templateId): Template
    {
        return $this->templateContract->update(
            new Template(
                new TemplateId($templateId->value()),
                new TemplateModelId($request->integer('entity_id')),
                new TemplateModelType(EntityModel::class),
                new TemplateFileTypeId($request->integer('file_type_id')),
                new TemplateFileSubtypeId($request->integer('file_subtype_id')),
                new TemplateName(''),
                new TemplatePath(''),
                new TemplateMime(''),
                new TemplateExtension(''),
                new TemplateSize(''),
                new TemplateMetadata([]),
                new TemplateSettings($request->input('settings'))
            ),
            $templateId
        );
    }
}
