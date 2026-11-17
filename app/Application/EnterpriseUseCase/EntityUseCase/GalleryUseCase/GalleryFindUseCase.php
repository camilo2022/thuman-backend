<?php

declare(strict_types=1);

namespace App\Application\EnterpriseUseCase\EntityUseCase\GalleryUseCase;

use App\Domain\Enterprise\Entity\Gallery\Gallery;
use App\Domain\Enterprise\Entity\Gallery\Contracts\GalleryContract;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;

final class GalleryFindUseCase
{
    private $galleryContract;

    public function __construct(GalleryContract $galleryContract)
    {
        $this->galleryContract = $galleryContract;
    }

    public function __invoke(GalleryId $galleryId): ?Gallery
    {
        return $this->galleryContract->find($galleryId);
    }
}
