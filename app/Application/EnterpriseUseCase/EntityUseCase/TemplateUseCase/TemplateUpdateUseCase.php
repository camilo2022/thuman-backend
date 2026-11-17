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
use App\Http\Requests\Enterprise\Entity\Template\TemplateUpdateRequest;
use App\Models\Entity as EntityModel;
use App\Models\Template as ModelsTemplate;
use Illuminate\Support\Facades\Storage;

final class TemplateUpdateUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateUpdateRequest $request, TemplateId $templateId): Template
    {
        $currentTemplate = ModelsTemplate::findOrFail($templateId->value());

        $file = $request->file('file');

        if ($request->hasFile('file') && $file?->isValid()) {
            $path = Storage::disk('public')->putFile("Enterprise/Entity/Template", $file);

            $imageInfo = @getimagesize($file->getPathname()) ?: null;

            $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $mime = $file->getMimeType();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            $metadata = [
                'client_original_name' => $file->getClientOriginalName(),
                'client_original_ext' => $file->getClientOriginalExtension(),
                'client_mime' => $file->getClientMimeType(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'error' => $file->getError(),
                'tmp_name' => $file->getPathname(),
                'real_path' => $file->getRealPath() ?: null,
                'file_exists' => file_exists($file->getPathname()),
                'is_readable' => is_readable($file->getPathname()),
                'is_writable' => is_writable($file->getPathname()),
                'hash_md5' => @md5_file($file->getPathname()),
                'hash_sha1' => @sha1_file($file->getPathname()),
                'hash_sha256' => @hash_file('sha256', $file->getPathname()),
                'image_info' => $imageInfo,
                'width' => $imageInfo[0] ?? null,
                'height' => $imageInfo[1] ?? null,
                'orientation' => isset($imageInfo[0], $imageInfo[1])
                    ? ($imageInfo[0] > $imageInfo[1] ? 'horizontal' : 'vertical')
                    : null,
                'stat' => file_exists($file->getPathname()) ? @stat($file->getPathname()) : null,
            ];
        } else {
            $name = $currentTemplate->name;

            // Importante: usa el path crudo de BD, no el accessor que devuelve URL
            $path = $currentTemplate->getRawOriginal('path');

            $mime = $currentTemplate->mime;
            $extension = $currentTemplate->extension;
            $size = (int) $currentTemplate->size;

            // metadata está casteado como object, lo convertimos a array
            $metadata = json_decode(json_encode($currentTemplate->metadata), true) ?? [];
        }

        return $this->templateContract->update(
            new Template(
                new TemplateId($templateId->value()),
                new TemplateModelId($request->integer('entity_id')),
                new TemplateModelType(EntityModel::class),
                new TemplateFileTypeId($request->integer('file_type_id')),
                new TemplateFileSubtypeId($request->integer('file_subtype_id')),
                new TemplateName($name),
                new TemplatePath($path),
                new TemplateMime($mime),
                new TemplateExtension($extension),
                new TemplateSize($size),
                new TemplateMetadata($metadata),
                new TemplateSettings($request->input('settings'))
            ),
            $templateId
        );
    }
}
