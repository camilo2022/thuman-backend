<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Vacant\Application\Application;
use App\Domain\Vacant\Application\Contracts\ApplicationContract;
use App\Domain\Vacant\Application\Values\ApplicationAnswers;
use App\Domain\Vacant\Application\Values\ApplicationCreatedAt;
use App\Domain\Vacant\Application\Values\ApplicationFormId;
use App\Domain\Vacant\Application\Values\ApplicationId;
use App\Domain\Vacant\Application\Values\ApplicationObservation;
use App\Domain\Vacant\Application\Values\ApplicationPersonId;
use App\Domain\Vacant\Application\Values\ApplicationQuestions;
use App\Domain\Vacant\Application\Values\ApplicationRelationships;
use App\Domain\Vacant\Application\Values\ApplicationSalary;
use App\Domain\Vacant\Application\Values\ApplicationStatus;
use App\Domain\Vacant\Application\Values\ApplicationUpdatedAt;
use App\Domain\Vacant\Application\Values\ApplicationVacantId;
use App\Domain\Vacant\Application\Values\VideoCreatedAt;
use App\Domain\Vacant\Application\Values\VideoDeletedAt;
use App\Domain\Vacant\Application\Values\VideoExtension;
use App\Domain\Vacant\Application\Values\VideoFileSubtypeId;
use App\Domain\Vacant\Application\Values\VideoFileTypeId;
use App\Domain\Vacant\Application\Values\VideoId;
use App\Domain\Vacant\Application\Values\VideoMetadata;
use App\Domain\Vacant\Application\Values\VideoMime;
use App\Domain\Vacant\Application\Values\VideoModelId;
use App\Domain\Vacant\Application\Values\VideoModelType;
use App\Domain\Vacant\Application\Values\VideoName;
use App\Domain\Vacant\Application\Values\VideoPath;
use App\Domain\Vacant\Application\Values\VideoSize;
use App\Domain\Vacant\Application\Values\VideoUpdatedAt;
use App\Domain\Vacant\Application\Video;
use App\Domain\Vacant\Values\VacantId;
use App\Models\Application as ApplicationModel;
use App\Models\File as FileModel;
use App\Http\Requests\Vacant\Application\ApplicationAllRequest;
use App\Http\Requests\Vacant\Application\ApplicationFindRequest;
use App\Http\Requests\Vacant\Application\ApplicationVerifyTokenRequest;
use App\Models\BankCertificate;
use App\Models\CompensationFundAffiliation;
use App\Models\ExamMedical;
use App\Models\ExamPolygraph;
use App\Models\FiscalDocumentCertificate;
use App\Models\HealthEntityAffiliation;
use App\Models\PensionFundAffiliation;
use App\Models\RiskManagerAffiliation;
use App\Service\Vacant\Application\ApplicationNotification;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApplicationRepository implements ApplicationContract
{
    private $applicationModel;
    private $fileModel;
    private $applicationNotification;

    public function __construct()
    {
        $this->applicationModel = new ApplicationModel;
        $this->fileModel = new FileModel;
        $this->applicationNotification = new ApplicationNotification;
    }

    public function all(string|null $search = null, int|null $perPage, ApplicationAllRequest $request, VacantId $vacantId): LengthAwarePaginator
    {
        try {
            $applications = $this->applicationModel
                ->with([
                    'person.dialing_country',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'form',
                    'vacant.contract_type.file_subtypes',
                    'person.profile.professional_experiences',
                    'person.city.departament.country',
                    'vacant.position.area',
                    'vacant.headquarter.entity.entity_type',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file',
                    'person.photo',
                    'contract.file_subtypes',
                    'person.profile.file',
                    'interview_initial',
                    'interview_psychotechnical',
                    'test_technical',
                    'test_psychotechnical',
                    'exam_medical',
                    'exam_polygraph'
                ])
                ->when($search, fn($query) => $query->search($search))
                ->when($request->filled('person_id'), function ($q) use ($request) {
                    $q->where('person_id', $request->integer('person_id'));
                })
                ->when($vacantId->value(), function ($q) use ($vacantId, $request) {
                    $q->where('vacant_id', $vacantId->value())
                        ->when($request->input('without_interview_initials'), function ($sq) {
                            $sq->whereDoesntHave('interview_initial')
                                ->where('status', ApplicationModel::REVIEW);
                        })
                        ->when($request->input('without_interview_psychotechnicals'), function ($sq) {
                            $sq->whereDoesntHave('interview_psychotechnical')
                                ->where('status', ApplicationModel::REVIEW);
                        })
                        ->when($request->input('without_test_technicals'), function ($sq) {
                            $sq->whereDoesntHave('test_technical')
                                ->where('status', ApplicationModel::TEST);
                        })
                        ->when($request->input('without_test_psychotechnicals'), function ($sq) {
                            $sq->whereDoesntHave('test_psychotechnical')
                                ->where('status', ApplicationModel::TEST);
                        })
                        ->when($request->input('without_exam_medicals'), function ($sq) {
                            $sq->whereDoesntHave('exam_medical')
                                ->where('status', ApplicationModel::FINALIST);
                        })
                        ->when($request->input('without_exam_polygraphs'), function ($sq) {
                            $sq->whereDoesntHave('exam_polygraph')
                                ->where('status', ApplicationModel::FINALIST);
                        });
                })
                ->when($request->filled('status'), function ($q) use ($request) {
                    $q->when($request->input('status') == ApplicationModel::FINALIST && $request->boolean('in_process'), function ($q) {
                        $q->where('status', ApplicationModel::FINALIST)
                            ->whereHas('exam_medical', fn($sq) => $sq->whereIn('result', [ExamMedical::APT, ExamMedical::APT_WITH_RECOMMENDATIONS]))
                            ->where(function ($q) {
                                $q->whereDoesntHave('vacant.position', function ($sq) {
                                    $sq->where('settings->polygraph', true);
                                })
                                    ->orWhere(function ($q) {
                                        $q->whereHas('vacant.position', function ($sq) {
                                            $sq->where('settings->polygraph', true);
                                        })
                                            ->whereHas(
                                                'exam_polygraph',
                                                fn($sq) =>
                                                $sq->whereIn('result', [
                                                    ExamPolygraph::APT
                                                ])
                                            );
                                    });
                            });
                    })
                        ->when($request->input('status') == ApplicationModel::HIRING && $request->boolean('in_process'), function ($q) {
                            $q->where('status', ApplicationModel::HIRING)
                                ->where(function ($q) {
                                    $q->whereHas('person.health_entity_affiliation', fn($sq) => $sq->where('status', HealthEntityAffiliation::PENDING))
                                        ->orWhereHas('person.pension_fund_affiliation', fn($sq) => $sq->where('status', PensionFundAffiliation::PENDING))
                                        ->orWhereHas('person.risk_manager_affiliation', fn($sq) => $sq->where('status', RiskManagerAffiliation::PENDING))
                                        ->orWhereHas('person.bank_certificate', fn($sq) => $sq->where('status', BankCertificate::PENDING))
                                        ->orWhereHas('person.compensation_fund_affiliation', fn($sq) => $sq->where('status', CompensationFundAffiliation::PENDING))
                                        ->orWhereHas('person.fiscal_document_certificate', fn($sq) => $sq->where('status', FiscalDocumentCertificate::PENDING));
                                });
                        })
                        ->when($request->input('status') && !$request->boolean('in_process'), function ($q) use ($request) {
                            $q->where('status', $request->input('status'));
                        })
                        ->when($request->filled('area_id'), function ($q) use ($request) {
                            $q->whereHas('contract.position', function ($q) use ($request) {
                                $q->where('area_id', $request->input('area_id'));
                            });
                        })
                        ->when($request->filled('entity_id'), function ($q) use ($request) {
                            $q->whereHas('contract.headquarter', function ($q) use ($request) {
                                $q->where('entity_id', $request->input('entity_id'));
                            });
                        })
                        ->when($request->filled('contract_type_id'), function ($q) use ($request) {
                            $q->whereHas('contract', function ($q) use ($request) {
                                $q->where('contract_type_id', $request->input('contract_type_id'));
                            });
                        });
                })
                ->orderBy('id', 'asc');

            return $applications->paginate($perPage ?? $applications->count());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al obtener las aplicaciones: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al obtener las aplicaciones: ' . $e->getMessage());
        }
    }

    public function find(ApplicationFindRequest $request, ApplicationId $applicationId): ?Application
    {
        try {
            $model = $this->applicationModel
                ->with([
                    'person.dialing_country',
                    'person.birth_city.departament.country',
                    'person.document_issue_city.departament.country',
                    'form',
                    'vacant.contract_type.file_subtypes',
                    'person.profile.professional_experiences',
                    'person.city.departament.country',
                    'vacant.position.area',
                    'vacant.headquarter.entity.entity_type',
                    'person.document_type',
                    'person.sex',
                    'person.gender',
                    'person.blood_type',
                    'person.marital_status',
                    'person.file',
                    'person.photo',
                    'person.profile.file',
                    'video_interview'
                ])
                ->when($applicationId->value(), function ($query) use ($applicationId) {
                    $query->where('id', $applicationId->value());
                })
                ->when(!$applicationId->value(), function ($query) use ($request) {
                    $query->where('person_id', $request->integer('person_id'))
                        ->where('vacant_id', $request->integer('vacant_id'));
                })
                ->firstOrFail();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La aplicación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la aplicacion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al buscar la aplicacion: ' . $e->getMessage());
        }
    }

    public function store(Application $application): Application
    {
        try {
            $model = $this->applicationModel
                ->create($this->mapToModelData($application));

            return $this->mapToEntity($model);
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al guardar la aplicacion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al guardar la aplicacion: ' . $e->getMessage());
        }
    }

    public function update(Application $application, ApplicationId $applicationId): Application
    {
        try {
            $model = tap($this->applicationModel->findOrFail($applicationId->value()))
                ->update($this->mapToModelData($application))
                ->refresh();

            return $this->mapToEntity($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La aplicación no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al actualizar la aplicacion: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al actualizar la aplicacion: ' . $e->getMessage());
        }
    }

    public function video(Video $video): Video
    {
        try {
            $model = $this->fileModel->updateOrCreate($this->mapToModelDataVideo($video, 'keys'), $this->mapToModelDataVideo($video, 'data'));

            return $this->mapToEntityVideo($model);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'El video no existe: ' . $e->getMessage());
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al crear/actualizar el video: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al crear/actualizar el video: ' . $e->getMessage());
        }
    }

    public function notifyToken(ApplicationId $applicationId): void
    {
        try {
            $model = $this->applicationModel->findOrFail($applicationId->value());

            $verification = $model->verifications()->where('verified', false)->latest()->first();

            if ($verification && !$verification->isExpired()) {
                throw new HttpException(409, 'Ya existe un token activo. No puede solicitar uno nuevo hasta que el actual expire o sea utilizado.');
            }

            $verification = $model->notifyToken();

            $emailId = $this->applicationNotification->sendEmail($model->person->email, implode(' ', array_filter([$model->person->first_name, $model->person->second_name, $model->person->first_last_name, $model->person->second_last_name])), ['code' => $verification->code]);
            $verification->attachExternalId($emailId);
        } catch (ModelNotFoundException $e) {
            throw new HttpException(404, 'La aplicación no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw new HttpException(500, 'Error al buscar la aplicación: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al enviar el token de acceso: ' . $e->getMessage());
        }
    }

    public function verifyToken(ApplicationVerifyTokenRequest $request, ApplicationId $applicationId): ?Application
    {
        try {
            $model = $this->applicationModel->findOrFail($applicationId->value());

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
            throw new HttpException(404, 'La aplicación no existe: ' . $e->getMessage());
        } catch (HttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new HttpException(500, 'Error inesperado al verificar el token: ' . $e->getMessage());
        }
    }

    private function mapToEntity($model): Application
    {
        return new Application(
            new ApplicationId($model->id),
            new ApplicationPersonId($model->person?->id ?? $model->person_id),
            new ApplicationVacantId($model->vacant?->id ?? $model->vacant_id),
            new ApplicationFormId($model->form_id),
            new ApplicationQuestions($model->questions),
            new ApplicationAnswers($model->answers),
            new ApplicationSalary($model->salary),
            new ApplicationObservation($model->observation),
            new ApplicationStatus($model->status),
            new ApplicationCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new ApplicationUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new ApplicationRelationships($model->person, $model->vacant, $model->form, $model->video_interview)
        );
    }

    private function mapToEntityVideo($model): Video
    {
        return new Video(
            new VideoId($model->id),
            new VideoModelId($model->model?->id ?? $model->model_id),
            new VideoModelType($model->model_type),
            new VideoFileTypeId($model->file_type?->id ?? $model->file_type_id),
            new VideoFileSubtypeId($model->file_subtype?->id ?? $model->file_subtype_id),
            new VideoName($model->name),
            new VideoPath($model->path),
            new VideoMime($model->mime),
            new VideoExtension($model->extension),
            new VideoSize($model->size),
            new VideoMetadata($model->metadata),
            new VideoCreatedAt($model->created_at?->format('Y-m-d H:i:s')),
            new VideoUpdatedAt($model->updated_at?->format('Y-m-d H:i:s')),
            new VideoDeletedAt($model->deleted_at?->format('Y-m-d H:i:s'))
        );
    }

    private function mapToModelData(Application $application): array
    {
        $array = [];

        if ($application->personId()->value()) {
            $array['person_id'] = $application->personId()->value();
        }

        if ($application->vacantId()->value()) {
            $array['vacant_id'] = $application->vacantId()->value();
        }

        if ($application->formId()->value()) {
            $array['form_id'] = $application->formId()->value();
        }

        if ($application->questions()->value()) {
            $array['questions'] = $application->questions()->value();
        }

        if ($application->answers()->value()) {
            $array['answers'] = $application->answers()->value();
        }

        if ($application->salary()->value()) {
            $array['salary'] = $application->salary()->value();
        }

        if ($application->observation()->value()) {
            $array['observation'] = $application->observation()->value();
        }

        if ($application->status()->value()) {
            $array['status'] = $application->status()->value();
        }

        return $array;
    }

    private function mapToModelDataVideo(Video $video, string $index): array
    {
        $array = [
            'keys' => [],
            'data' => []
        ];

        if ($video->modelId()->value()) {
            $array['keys']['model_id'] = $video->modelId()->value();
        }

        if ($video->modelType()->value()) {
            $array['keys']['model_type'] = $video->modelType()->value();
        }

        if ($video->fileTypeId()->value()) {
            $array['data']['file_type_id'] = $video->fileTypeId()->value();
        }

        if ($video->fileSubtypeId()->value()) {
            $array['data']['file_subtype_id'] = $video->fileSubtypeId()->value();
        }

        if ($video->name()->value()) {
            $array['data']['name'] = $video->name()->value();
        }

        if ($video->path()->value()) {
            $array['data']['path'] = $video->path()->value();
        }

        if ($video->mime()->value()) {
            $array['data']['mime'] = $video->mime()->value();
        }

        if ($video->extension()->value()) {
            $array['data']['extension'] = $video->extension()->value();
        }

        if ($video->size()->value()) {
            $array['data']['size'] = $video->size()->value();
        }

        if ($video->metadata()->value()) {
            $array['data']['metadata'] = $video->metadata()->value();
        }

        return $array[$index];
    }
}
