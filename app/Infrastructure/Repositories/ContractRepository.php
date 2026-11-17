<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contract\Contracts\ContractContract;
use App\Models\Contract as ContractModel;
use App\Models\File as FileModel;
use App\Domain\Contract\Contract;
use App\Domain\Contract\Image;
use App\Domain\Contract\Values\ContractApplicationId;
use App\Domain\Contract\Values\ContractContractTypeId;
use App\Domain\Contract\Values\ContractCreatedAt;
use App\Domain\Contract\Values\ContractDeletedAt;
use App\Domain\Contract\Values\ContractId;
use App\Domain\Contract\Values\ContractEndDate;
use App\Domain\Contract\Values\ContractFileSubtypesId;
use App\Domain\Contract\Values\ContractHeadquarterId;
use App\Domain\Contract\Values\ContractLegalRepresentativeSignedAt;
use App\Domain\Contract\Values\ContractLegalRepresentativeStatus;
use App\Domain\Contract\Values\ContractPaymentPeriodId;
use App\Domain\Contract\Values\ContractPersonId;
use App\Domain\Contract\Values\ContractPersonObservation;
use App\Domain\Contract\Values\ContractPersonSignedAt;
use App\Domain\Contract\Values\ContractPersonStatus;
use App\Domain\Contract\Values\ContractPositionId;
use App\Domain\Contract\Values\ContractRelationships;
use App\Domain\Contract\Values\ContractSalary;
use App\Domain\Contract\Values\ContractSettings;
use App\Domain\Contract\Values\ContractStartDate;
use App\Domain\Contract\Values\ContractTransportAllowance;
use App\Domain\Contract\Values\ContractUpdatedAt;
use App\Domain\Contract\Values\ContractUuid;
use App\Domain\Contract\Values\ImageCreatedAt;
use App\Domain\Contract\Values\ImageDeletedAt;
use App\Domain\Contract\Values\ImageExtension;
use App\Domain\Contract\Values\ImageFileSubtypeId;
use App\Domain\Contract\Values\ImageFileTypeId;
use App\Domain\Contract\Values\ImageId;
use App\Domain\Contract\Values\ImageMetadata;
use App\Domain\Contract\Values\ImageMime;
use App\Domain\Contract\Values\ImageModelId;
use App\Domain\Contract\Values\ImageModelType;
use App\Domain\Contract\Values\ImageName;
use App\Domain\Contract\Values\ImagePath;
use App\Domain\Contract\Values\ImageSize;
use App\Domain\Contract\Values\ImageUpdatedAt;
use App\Http\Requests\Contract\ContractAllRequest;
use App\Http\Requests\Contract\ContractFindRequest;
use App\Http\Requests\Contract\ContractNotifyTokenRequest;
use App\Http\Requests\Contract\ContractVerifyTokenRequest;
use App\Service\Contract\ContractNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Illuminate\Log\log;

final class ContractRepository implements ContractContract
{
    private $contractModel;
    private $fileModel;
    private $contractNotification;

    public function __construct()
    {
        $this->contractModel = new ContractModel;
        $this->fileModel = new FileModel;
        $this->contractNotification = new ContractNotification;
    }

    public function all(string|null $search = null, int|null $perPage, ContractAllRequest $request): LengthAwarePaginator
    {
        try {
            $contracts = $this->contractModel
                ->when($request->boolean('with_trashed'), fn($query) => $query->withTrashed())
                ->with([
                    'application', 'person' => ['document_type', 'photo', 'dialing_country'], 'contract_type', 'position' => ['area'], 'pdf',
                    'headquarter' => ['city' => ['departament'], 'entity' => ['enterprise', 'country']], 'payment_period', 'file_subtypes'
                ])
                ->when($request->filled('person_status'), function ($q) use ($request) {
                    $q->where('person_status', $request->input('person_status'));
                })
                ->when($request->filled('legal_representative_status'), function ($q) use ($request) {
                    $q->where('legal_representative_status', $request->input('legal_representative_status'));
                })
                ->when($request->filled('area_id'), function ($q) use ($request) {
                    $q->whereHas('position', function ($q) use ($request) {
                        $q->where('area_id', $request->input('area_id'));
                    });
                })
                ->when($request->filled('entity_id'), function ($q) use ($request) {
                    $q->whereHas('headquarter', function ($q) use ($request) {
                        $q->where('entity_id', $request->input('entity_id'));
                    });
                })
                ->when($request->filled('contract_type_id'), function ($q) use ($request) {
                    $q->where('contract_type_id', $request->input('contract_type_id'));
                })
                ->when($search, fn($query) => $query->search($search))
                ->orderBy('id', 'asc');

            return $contracts->paginate($perPage ?? $contracts->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener los contratos: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener los contratos: ' . $e->getMessage());
        }
    }

    public function find(ContractFindRequest $request, ContractId $contractId): ?Contract
    {
        try {
            $model = $this->contractModel
                ->with([
                    'application', 'person' => ['document_type', 'photo', 'dialing_country'], 'contract_type', 'position' => ['area'], 'pdf',
                    'headquarter' => ['city' => ['departament'], 'entity' => ['enterprise', 'country']], 'payment_period', 'file_subtypes'
                ])
                ->where($contractId->value() ? 'id' : ($request->filled('application_id') ? 'application_id' : ($request->filled('person_id') ? 'person_id' : 'uuid')), $contractId->value() ?: ($request->filled('application_id') ? $request->input('application_id') : ($request->filled('person_id') ? $request->input('person_id') : $request->input('contract_uuid'))))->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar el contrato: ' . $e->getMessage());
        }
    }

    public function store(Contract $contract): Contract
    {
        try {
            $model = $this->contractModel
                ->create($this->mapToModelData($contract));

            $model->file_subtypes()->sync(array_filter(
                $contract->fileSubtypesId()->value()
            ));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar el contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar el contrato: ' . $e->getMessage());
        }
    }

    public function update(Contract $contract, ContractId $contractId): Contract
    {
        try {
            $model = tap($this->contractModel->findOrFail($contractId->value()))
                ->update($this->mapToModelData($contract))
                ->refresh();

            $model->file_subtypes()->sync(array_filter(
                $contract->fileSubtypesId()->value()
            ));

            if($model->person_status == ContractModel::SIGNED && $model->legal_representative_status == ContractModel::APPROVED && $model->person->signature && !$model->person_signature){
                $original = $model->person->signature->getRawOriginal('path');
                $name = Str::random(40).'.'.$model->person->signature->extension;
                $path = 'Contract/' . $name;

                if (Storage::disk('public')->exists($original)) {
                    Storage::disk('public')->copy($original, $path);
                }

                $this->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($contractId->value()),
                        new ImageModelType(ContractModel::class),
                        new ImageFileTypeId(100),
                        new ImageFileSubtypeId($model->person->signature->file_subtype_id),
                        new ImageName(pathinfo($name, PATHINFO_FILENAME)),
                        new ImagePath($path),
                        new ImageMime($model->person->signature->mime),
                        new ImageExtension($model->person->signature->extension),
                        new ImageSize($model->person->signature->size),
                        new ImageMetadata($model->person->signature->metadata)
                    )
                );
            }

            if($model->legal_representative_status == ContractModel::SIGNED && !$model->legal_representative_signature){
                $original = $model->headquarter->entity->person->signature->getRawOriginal('path');
                $name = Str::random(40).'.'.$model->headquarter->entity->person->signature->extension;
                $path = 'Contract/' . $name;

                if (Storage::disk('public')->exists($original)) {
                    Storage::disk('public')->copy($original, $path);
                }

                $this->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($contractId->value()),
                        new ImageModelType(ContractModel::class),
                        new ImageFileTypeId(97),
                        new ImageFileSubtypeId($model->headquarter->entity->person->signature->file_subtype_id),
                        new ImageName(pathinfo($name, PATHINFO_FILENAME)),
                        new ImagePath($path),
                        new ImageMime($model->headquarter->entity->person->signature->mime),
                        new ImageExtension($model->headquarter->entity->person->signature->extension),
                        new ImageSize($model->headquarter->entity->person->signature->size),
                        new ImageMetadata($model->headquarter->entity->person->signature->metadata)
                    )
                );

                $pdf = $this->generate($model);

                $path = 'Contract/'.Str::random(40).'.pdf';

                Storage::disk('public')->put($path, $pdf->output());

                $this->image(
                    new Image(
                        new ImageId(null),
                        new ImageModelId($model->id),
                        new ImageModelType(ContractModel::class),
                        new ImageFileTypeId(98),
                        new ImageFileSubtypeId(131),
                        new ImageName($model->uuid),
                        new ImagePath($path),
                        new ImageMime('application/pdf'),
                        new ImageExtension('pdf'),
                        new ImageSize(1),
                        new ImageMetadata([
                            'generate' => true,
                            'date_generate' => Carbon::now()
                        ])
                    )
                );
            }

            if($model->person_status === ContractModel::WITHDRAWNED or $model->legal_representative_status === ContractModel::REJECTED) {
                $model->delete();
            }

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el contrato: ' . $e->getMessage());
        }
    }

    public function image(Image $image): Image
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataImage($image, 'keys'), $this->mapToModelDataImage($image, 'data'));

            return $this->mapToEntityImage($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La firma no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar la firma: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar la firma: ' . $e->getMessage());
        }
    }

    public function notifyToken(ContractNotifyTokenRequest $request, ContractId $contractId): void
    {
        try {
            $model = $this->contractModel
                ->when($contractId->value(), function ($query) use ($contractId) {
                    $query->where('id', $contractId->value());
                })
                ->when(!$contractId->value(), function ($query) use ($request) {
                    $query->when($request->filled('application_id'), function ($query) use ($request) {
                        $query->where('application_id', $request->input('application_id'));
                    });
                })
                ->firstOrFail();

            $verification = $model->verifications()->where('verified', false)->latest()->first();

            if ($verification && !$verification->isExpired()) {
                throw new HttpException(409, 'Ya existe un token activo. No puede solicitar uno nuevo hasta que el actual expire o sea utilizado.');
            }

            $verification = $model->notifyToken();

            $emailId = $this->contractNotification->sendEmail($model->person->email, implode(' ', array_filter([$model->person->first_name, $model->person->second_name, $model->person->first_last_name, $model->person->second_last_name])), [ 'code' => $verification->code ]);
            $verification->attachExternalId($emailId);

        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contrato no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar el contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el token de acceso: ' . $e->getMessage());
        }
    }

    public function verifyToken(ContractVerifyTokenRequest $request, ContractId $contractId): ?Contract
    {
        try {
            $model = $this->contractModel->findOrFail($contractId->value());

            $verification = $model->verifications()->where('code', $request->input('code'))->where('verified', false)->latest()->first();

            if (!$verification) {
                throw new HttpException(422, 'El token ingresado no es válido.');
            }

            if ($verification->isExpired()) {
                throw new HttpException(410, 'El token ha expirado. Solicite uno nuevo.');
            }

            $verification->verifyCode();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contrato no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al verificar el token: ' . $e->getMessage());
        }
    }

    public function preview(ContractId $contractId)
    {
        try {
            $model = $this->contractModel->with(['application' => ['vacant' => ['headquarter.entity' => ['person' => ['signature'], 'enterprise']], 'person' => ['signature']]])->withTrashed()
                ->findOrFail($contractId->value());

            $pdf = $this->generate($model);

            return base64_encode($pdf->output());
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El contrato no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar el contrato: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar el contrato: ' . $e->getMessage());
        }
    }

    private function generate(ContractModel $model)
    {
        try {
            $model->load(['application' => ['vacant' => ['headquarter' => ['entity' => ['person' => ['signature'], 'enterprise']]], 'person' => ['signature']]]);

            $html = '';

            foreach ($model->settings?->documents ?? [] as &$document) {
                $data = (object)[
                    'contract' => $model
                ];
                foreach ($document->template?->settings?->sections ?? [] as &$section) {
                    foreach ($section->subsections ?? [] as &$subsection) {
                        if (isset($subsection->items)) {
                            foreach ($subsection->items as &$item) {
                                $item->content = $this->renderTemplate($item->content, $document->variables, $data);
                            }
                        } else {
                            $subsection->content = $this->renderTemplate($subsection->content, $document->variables, $data);
                        }
                    }
                }
                $settings = json_decode(json_encode($document->template->settings), true);
                $html .= view('Contract.ContractPreview', [
                    'settings' => $settings,
                    'template' => (array) $document->template
                ])->render();
            }

            return Pdf::loadHTML($html)->setOptions([
                'isRemoteEnabled' => true
            ]);
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al contruir el contrato: ' . $e->getMessage());
        }
    }

    private function renderTemplate(string $content, array $variablesConfig, object $data): string
    {
        $map = [];

        foreach ($variablesConfig as $variable) {
            $variable = (object) $variable;
            $basePath = $variable->path ?? null;
            $baseData = $basePath ? data_get($data, $basePath) : $data;
            foreach ($variable->groups as $group) {
                $group = (object) $group;
                $groupPath = $group->path ?? null;
                $groupData = $groupPath ? data_get($baseData, $groupPath) : $baseData;
                foreach ($group->fields as $field) {
                    $field = (object) $field;
                    $value = data_get($groupData, $field->field);
                    if (($field->type ?? null) === 'file' && !empty($value)) {
                        $map[$field->key] = '<img src="' . $value . '" class="img">';
                    } else {
                        $map[$field->key] = $value ?? '';
                    }
                }
            }
        }

        $text = preg_replace_callback('/<span class="var-chip"[^>]*data-key="([^"]+)"[^>]*>.*?<\/span>/s', fn ($match) => $map[$match[1]] ?? '', $content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function mapToEntity($model): Contract
    {
        return new Contract(
            new ContractId($model->id),
            new ContractUuid($model->uuid),
            new ContractApplicationId($model->application_id),
            new ContractPersonId($model->person_id),
            new ContractHeadquarterId($model->headquarter_id),
            new ContractContractTypeId($model->contract_type_id),
            new ContractPositionId($model->position_id),
            new ContractSalary($model->salary),
            new ContractTransportAllowance($model->transport_allowance),
            new ContractPaymentPeriodId($model->payment_period_id),
            new ContractStartDate($model->start_date?->format('Y-m-d')),
            new ContractEndDate($model->end_date?->format('Y-m-d')),
            new ContractPersonObservation($model->person_observation),
            new ContractPersonSignedAt($model->person_signed_at?->format('Y-m-d H:i:s')),
            new ContractPersonStatus($model->person_status),
            new ContractLegalRepresentativeSignedAt($model->legal_representative_signed_at?->format('Y-m-d H:i:s')),
            new ContractLegalRepresentativeStatus($model->legal_representative_status),
            new ContractSettings($model->settings),
            new ContractFileSubtypesId($model->file_subtypes->modelKeys()),
            new ContractCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ContractUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ContractDeletedAt($model->deleted_at?->format('Y-m-d H:i:s')),
            new ContractRelationships($model->application, $model->person, $model->headquarter, $model->contract_type, $model->position, $model->payment_period, $model->signed_person, $model->signed_legal_representative, $model->pdf, $model->file_subtypes)
        );
    }

    private function mapToEntityImage($model): Image
    {
        return new Image(
            new ImageId($model->id),
            new ImageModelId($model->model?->id ?? $model->model_id),
            new ImageModelType($model->model_type),
            new ImageFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new ImageFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new ImageName($model->name),
            new ImagePath($model->path),
            new ImageMime($model->mime),
            new ImageExtension($model->extension),
            new ImageSize($model->size),
            new ImageMetadata($model->metadata),
            new ImageCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ImageUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ImageDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Contract $contract): array
    {
        $array = [];

        if ($contract->applicationId()->value()) {
            $array['application_id'] = $contract->applicationId()->value();
        }

        if ($contract->personId()->value()) {
            $array['person_id'] = $contract->personId()->value();
        }

        if ($contract->headquarterId()->value()) {
            $array['headquarter_id'] = $contract->headquarterId()->value();
        }

        if ($contract->contractTypeId()->value()) {
            $array['contract_type_id'] = $contract->contractTypeId()->value();
        }

        if ($contract->positionId()->value()) {
            $array['position_id'] = $contract->positionId()->value();
        }

        if ($contract->salary()->value()) {
            $array['salary'] = $contract->salary()->value();
        }

        if ($contract->transportAllowance()->value()) {
            $array['transport_allowance'] = $contract->transportAllowance()->value();
        }

        if ($contract->paymentPeriodId()->value()) {
            $array['payment_period_id'] = $contract->paymentPeriodId()->value();
        }

        if ($contract->startDate()->value()) {
            $array['start_date'] = $contract->startDate()->value();
        }

        if ($contract->endDate()->value()) {
            $array['end_date'] = $contract->endDate()->value();
        }

        if ($contract->personObservation()->value()) {
            $array['person_observation'] = $contract->personObservation()->value();
        }

        if ($contract->personSignedAt()->value()) {
            $array['person_signed_at'] = $contract->personSignedAt()->value();
        }

        if ($contract->personStatus()->value()) {
            $array['person_status'] = $contract->personStatus()->value();
        }

        if ($contract->legalRepresentativeSignedAt()->value()) {
            $array['legal_representative_signed_at'] = $contract->legalRepresentativeSignedAt()->value();
        }

        if ($contract->legalRepresentativeStatus()->value()) {
            $array['legal_representative_status'] = $contract->legalRepresentativeStatus()->value();
        }

        if ($contract->settings()->value()) {
            $array['settings'] = $contract->settings()->value();
        }

        return $array;
    }

    private function mapToModelDataImage(Image $image, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($image->modelId()->value()) {
            $array['keys']['model_id'] = $image->modelId()->value();
        }

        if ($image->modelType()->value()) {
            $array['keys']['model_type'] = $image->modelType()->value();
        }

        if ($image->fileTypeId()->value()) {
            $array['keys']['file_type_id'] = $image->fileTypeId()->value();
        }

        if ($image->fileSubtypeId()->value()) {
            $array['keys']['file_subtype_id'] = $image->fileSubtypeId()->value();
        }

        if ($image->name()->value()) {
            $array['data']['name'] = $image->name()->value();
        }

        if ($image->path()->value()) {
            $array['data']['path'] = $image->path()->value();
        }

        if ($image->mime()->value()) {
            $array['data']['mime'] = $image->mime()->value();
        }

        if ($image->extension()->value()) {
            $array['data']['extension'] = $image->extension()->value();
        }

        if ($image->size()->value()) {
            $array['data']['size'] = $image->size()->value();
        }

        if ($image->metadata()->value()) {
            $array['data']['metadata'] = $image->metadata()->value();
        }

        return $array[$index];
    }
}
