<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Enterprise\Entity\Template\Contracts\TemplateContract;
use App\Models\Template as TemplateModel;
use App\Models\Entity as EntityModel;
use App\Domain\Enterprise\Entity\Template\Template;
use App\Domain\Enterprise\Entity\Template\Values\TemplateCreatedAt;
use App\Domain\Enterprise\Entity\Template\Values\TemplateDeletedAt;
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
use App\Domain\Enterprise\Entity\Template\Values\TemplateRelationships;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSettings;
use App\Domain\Enterprise\Entity\Template\Values\TemplateSize;
use App\Domain\Enterprise\Entity\Template\Values\TemplateUpdatedAt;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Template\TemplateAllRequest;
use App\Http\Requests\Enterprise\Entity\Template\TemplateFindRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class TemplateRepository implements TemplateContract
{
    private $templateModel;

    public function __construct()
    {
        $this->templateModel = new TemplateModel;
    }

    public function all(string|null $search = null, int|null $perPage, EntityId $entityId, TemplateAllRequest $request): LengthAwarePaginator
    {
        try {
            $templates = $this->templateModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with(['model', 'file_type', 'file_subtype'])
                ->whereHasMorph('model', [EntityModel::class], function ($query) use ($entityId) {
                    $query->where('model_id', $entityId->value());
                })
                ->where('file_type_id', 98)
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $templates->paginate($perPage ?? $templates->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las plantillas de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las plantillas de la entidad: ' . $e->getMessage());
        }
    }

    public function find(TemplateFindRequest $request, TemplateId $templateId): ?Template
    {
        try {
            $model = $this->templateModel
                ->with(['model', 'file_type', 'file_subtype'])
                ->when($templateId->value(), function ($query) use ($templateId) {
                    $query->where('id', $templateId->value());
                })
                ->when(!$templateId->value(), function ($query) use ($request) {
                    $query->when($request->filled('entity_id'), function ($query) use ($request) {
                        $query->whereHasMorph('model', [EntityModel::class], function ($query) use ($request) {
                            $query->where('model_id', $request->input('entity_id'));
                        });
                    })
                    ->when($request->filled('file_type_id'), function ($query) use ($request) {
                        $query->where('file_type_id', $request->input('file_type_id'));
                    })
                    ->when($request->filled('file_subtype_id'), function ($query) use ($request) {
                        $query->where('file_subtype_id', $request->input('file_subtype_id'));
                    });
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plantilla de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    public function store(Template $template): Template
    {
        try {
            $model = $this->templateModel
                ->create($this->mapToModelData($template));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    public function update(Template $template, TemplateId $templateId): Template
    {
        try {
            $model = tap($this->templateModel->findOrFail($templateId->value()))
                ->update($this->mapToModelData($template))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plantilla de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    public function delete(TemplateId $templateId): Template
    {
        try {
            $model = tap($this->templateModel->findOrFail($templateId->value()), fn($template) => $template->delete());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plantilla de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    public function restore(TemplateId $templateId): Template
    {
        try {
            $model = tap($this->templateModel->withTrashed()->findOrFail($templateId->value()), fn($template) => $template->restore());

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plantilla de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al eliminar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al eliminar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    public function preview(TemplateId $templateId)
    {
        try {
            $model = $this->templateModel->withTrashed()
                ->with(['model', 'file_type', 'file_subtype'])
                ->findOrFail($templateId->value());

            $settings = json_decode(json_encode($model->settings), true);

            $pdf = Pdf::loadView('Enterprise.Entity.Template.TemplatePreview', [
                'settings' => $settings,
                'template' => $model
            ])->setOptions([
                'isRemoteEnabled' => true
            ]);

            return base64_encode($pdf->output());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La plantilla de la entidad no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la plantilla de la entidad: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la plantilla de la entidad: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Template
    {
        return new Template(
            new TemplateId($model->id),
            new TemplateModelId($model->model?->id ?? $model->model_id),
            new TemplateModelType($model->model_type),
            new TemplateFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new TemplateFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new TemplateName($model->name),
            new TemplatePath($model->path),
            new TemplateMime($model->mime),
            new TemplateExtension($model->extension),
            new TemplateSize($model->size),
            new TemplateMetadata($model->metadata),
            new TemplateSettings($model->settings),
            new TemplateCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new TemplateUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new TemplateDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new TemplateRelationships($model->model, $model->file_type, $model->file_subtype)
        );
    }

    private function mapToModelData(Template $template): array
    {
        $array = [];

        if ($template->modelId()->value()) {
            $array['model_id'] = $template->modelId()->value();
        }

        if ($template->modelType()->value()) {
            $array['model_type'] = $template->modelType()->value();
        }

        if ($template->fileTypeId()->value()) {
            $array['file_type_id'] = $template->fileTypeId()->value();
        }

        if ($template->fileSubtypeId()->value()) {
            $array['file_subtype_id'] = $template->fileSubtypeId()->value();
        }

        if ($template->name()->value()) {
            $array['name'] = $template->name()->value();
        }

        if ($template->path()->value()) {
            $array['path'] = $template->path()->value();
        }

        if ($template->mime()->value()) {
            $array['mime'] = $template->mime()->value();
        }

        if ($template->extension()->value()) {
            $array['extension'] = $template->extension()->value();
        }

        if ($template->size()->value()) {
            $array['size'] = $template->size()->value();
        }

        if ($template->metadata()->value()) {
            $array['metadata'] = $template->metadata()->value();
        }

        if ($template->settings()->value()) {
            $array['settings'] = $template->settings()->value();
        }

        return $array;
    }
}
