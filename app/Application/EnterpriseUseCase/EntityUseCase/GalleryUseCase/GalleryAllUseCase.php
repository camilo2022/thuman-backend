<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase;

use App\Domain\Enterprise\Entity\Gallery\Contracts\GalleryContract;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class GalleryAllUseCase
{
    private $galleryContract;

    public function __construct(GalleryContract $galleryContract)
    {
        $this->galleryContract = $galleryContract;
    }

    public function __invoke(GalleryAllRequest $request, EntityId $entityId): LengthAwarePaginator
    {
        return $this->galleryContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $entityId, $request);
    }
}
