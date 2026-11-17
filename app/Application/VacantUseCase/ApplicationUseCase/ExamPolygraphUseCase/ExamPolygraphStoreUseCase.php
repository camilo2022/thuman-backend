<?php

declare(strict_types=1);

namespace App\Application\VacantUseCase\ApplicationUseCase\ExamPolygraphUseCase;

use App\Domain\Vacant\Application\ExamPolygraph\Contracts\ExamPolygraphContract;
use App\Domain\Vacant\Application\ExamPolygraph\ExamPolygraph;
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
use App\Http\Requests\Vacant\Application\ExamPolygraph\ExamPolygraphStoreRequest;
use App\Models\ExamPolygraph as ExamPolygraphModel;

final class ExamPolygraphStoreUseCase
{
    private $examPolygraphContract;

    public function __construct(ExamPolygraphContract $examPolygraphContract)
    {
        $this->examPolygraphContract = $examPolygraphContract;
    }

    public function __invoke(ExamPolygraphStoreRequest $request): ExamPolygraph
    {
        return $this->examPolygraphContract->store(
            new ExamPolygraph(
                new ExamPolygraphId(null),
                new ExamPolygraphApplicationId($request->integer('application_id')),
                new ExamPolygraphDateTime($request->input('date_time')),
                new ExamPolygraphCityId($request->integer('city_id')),
                new ExamPolygraphServiceProviderId($request->integer('service_provider_id')),
                new ExamPolygraphAddress($request->input('address')),
                new ExamPolygraphNeighborhood($request->input('neighborhood')),
                new ExamPolygraphResult(ExamPolygraphModel::PENDING),
                new ExamPolygraphDescription($request->input('description')),
                new ExamPolygraphIndication($request->input('indication')),
                new ExamPolygraphStatus(ExamPolygraphModel::SCHEDULED)
            )
        );
    }
}
