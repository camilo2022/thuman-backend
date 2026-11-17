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
use App\Http\Requests\Enterprise\Entity\Template\TemplateStoreRequest;
use App\Models\Entity as EntityModel;
use Illuminate\Support\Facades\Storage;

final class TemplateStoreUseCase
{
    private $templateContract;

    public function __construct(TemplateContract $templateContract)
    {
        $this->templateContract = $templateContract;
    }

    public function __invoke(TemplateStoreRequest $request): Template
    {
        return $this->templateContract->store(
            new Template(
                new TemplateId(null),
                new TemplateModelId($request->integer('entity_id')),
                new TemplateModelType(EntityModel::class),
                new TemplateFileTypeId($request->integer('file_type_id')),
                new TemplateFileSubtypeId($request->integer('file_subtype_id')),
                new TemplateName(pathinfo($request->file('file')->getClientOriginalName(), PATHINFO_FILENAME)),
                new TemplatePath(Storage::disk('public')->putFile("Enterprise/Entity/Template", $request->file('file'))),
                new TemplateMime($request->file('file')->getMimeType()),
                new TemplateExtension($request->file('file')->getClientOriginalExtension()),
                new TemplateSize($request->file('file')->getSize()),
                new TemplateMetadata([
                    'client_original_name' => $request->file('file')->getClientOriginalName(),
                    'client_original_ext' => $request->file('file')->getClientOriginalExtension(),
                    'client_mime' => $request->file('file')->getClientMimeType(),
                    'mime' => $request->file('file')->getMimeType(),
                    'size' => $request->file('file')->getSize(),
                    'error' => $request->file('file')->getError(),
                    'tmp_name' => $request->file('file')->getPathname(),
                    'real_path' => $request->file('file')->getRealPath() ?: null,
                    'file_exists' => file_exists($request->file('file')->getPathname()),
                    'is_readable' => is_readable($request->file('file')->getPathname()),
                    'is_writable' => is_writable($request->file('file')->getPathname()),
                    'hash_md5' => @md5_file($request->file('file')->getPathname()),
                    'hash_sha1' => @sha1_file($request->file('file')->getPathname()),
                    'hash_sha256' => @hash_file('sha256', $request->file('file')->getPathname()),
                    'image_info' => ($info = @getimagesize($request->file('file')->getPathname())) ?: null,
                    'width' => $info[0] ?? null,
                    'height' => $info[1] ?? null,
                    'orientation' => (isset($info[0], $info[1]))
                                                ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                : null,
                    'stat' => ($request->file('file')->getPathname() && file_exists($request->file('file')->getPathname())) ? @stat($request->file('file')->getPathname()) : null,
                ]),
                new TemplateSettings($request->input('settings', []))
            )
        );
    }
}
