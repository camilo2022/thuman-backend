<?php

declare(strict_types=1);

namespace App\Application\BloodTypeUseCase;

use App\Domain\BloodType\Contracts\BloodTypeContract;
use App\Http\Requests\BloodType\BloodTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class BloodTypeAllUseCase
{
    private $bloodTypeContract;

    public function __construct(BloodTypeContract $bloodTypeContract)
    {
        $this->bloodTypeContract = $bloodTypeContract;
    }

    public function __invoke(BloodTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->bloodTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
