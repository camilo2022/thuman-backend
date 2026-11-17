<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase;

use App\Domain\Vacant\Application\ExamPolygraph\Contracts\ExamPolygraphContract;
use App\Domain\Vacant\Application\ExamPolygraph\ExamPolygraph;
use App\Domain\Vacant\Application\ExamPolygraph\File;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphAddress;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphApplicationId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphCityId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDateTime;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphDescription;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphServiceProviderId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphIndication;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphNeighborhood;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphResult;
use App\Domain\Vacant\Application\ExamPolygraph\Values\ExamPolygraphStatus;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileExtension;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileFileSubtypeId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileFileTypeId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileMetadata;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileMime;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileModelId;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileModelType;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileName;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FilePath;
use App\Domain\Vacant\Application\ExamPolygraph\Values\FileSize;
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphReviewerRequest;
use App\Models\ExamPolygraph as ExamPolygraphModel;
use Illuminate\Support\Facades\Storage;

final class ExamPolygraphReviewerUseCase
{
    private $examPolygraphContract;

    public function __construct(ExamPolygraphContract $examPolygraphContract)
    {
        $this->examPolygraphContract = $examPolygraphContract;
    }

    public function __invoke(ExamPolygraphReviewerRequest $request, ExamPolygraphId $examPolygraphId): ExamPolygraph
    {
        $examPolygraph = $this->examPolygraphContract->update(
            new ExamPolygraph(
                new ExamPolygraphId(null),
                new ExamPolygraphApplicationId($request->integer('application_id')),
                new ExamPolygraphDateTime($request->input('date_time')),
                new ExamPolygraphCityId($request->integer('city_id')),
                new ExamPolygraphServiceProviderId($request->integer('service_provider_id')),
                new ExamPolygraphAddress($request->input('address')),
                new ExamPolygraphNeighborhood($request->input('neighborhood')),
                new ExamPolygraphResult($request->input('result')),
                new ExamPolygraphDescription($request->input('description')),
                new ExamPolygraphIndication($request->input('indication')),
                new ExamPolygraphStatus(ExamPolygraphModel::COMPLETED)
            ),
            $examPolygraphId
        );

        if ($request->hasFile('file.file')) {
            $this->examPolygraphContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($examPolygraph->id()->value()),
                    new FileModelType(ExamPolygraphModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Vacant/Application/ExamPolygraph", $request->file('file.file'))),
                    new FileMime($request->file('file.file')->getMimeType()),
                    new FileExtension($request->file('file.file')->getClientOriginalExtension()),
                    new FileSize($request->file('file.file')->getSize()),
                    new FileMetadata([
                        'client_original_name' => $request->file('file.file')->getClientOriginalName(),
                        'client_original_ext' => $request->file('file.file')->getClientOriginalExtension(),
                        'client_mime' => $request->file('file.file')->getClientMimeType(),
                        'mime' => $request->file('file.file')->getMimeType(),
                        'size' => $request->file('file.file')->getSize(),
                        'error' => $request->file('file.file')->getError(),
                        'tmp_name' => $request->file('file.file')->getPathname(),
                        'real_path' => $request->file('file.file')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('file.file')->getPathname()),
                        'is_readable' => is_readable($request->file('file.file')->getPathname()),
                        'is_writable' => is_writable($request->file('file.file')->getPathname()),
                        'hash_md5' => @md5_file($request->file('file.file')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('file.file')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('file.file')->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file('file.file')->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file('file.file')->getPathname() && file_exists($request->file('file.file')->getPathname())) ? @stat($request->file('file.file')->getPathname()) : null,
                    ])
                )
            );
        }

        return $examPolygraph;
    }
}
