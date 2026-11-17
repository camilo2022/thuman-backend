<?php

namespace App\Http\Resources\Enterprise\Entity\OrganizationalStructure;

use App\Models\OrganizationalStructure;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class OrganizationalStructureCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'organizationalStructures' => $this->collection->map(function ($organizationalStructure) {
                return [
                    'id' => $organizationalStructure->id,
                    'entity_id' => $organizationalStructure->entity_id,
                    'entity' => $organizationalStructure->entity,
                    'position_id' => $organizationalStructure->position_id,
                    'position' => $organizationalStructure->position,
                    'parent_position_id' => $organizationalStructure->parent_position_id,
                    'parent_position' => $organizationalStructure->parentPosition,
                    'start_date' => $this->formatDate($organizationalStructure->start_date, 'Y-m-d'),
                    'end_date' => $this->formatDate($organizationalStructure->end_date, 'Y-m-d'),
                    'reason' => $organizationalStructure->reason,
                    'status' => $organizationalStructure->status,
                    'created_at' => $this->formatDate($organizationalStructure->created_at),
                    'updated_at' => $this->formatDate($organizationalStructure->updated_at)
                ];
            }),
            'statuses' => OrganizationalStructure::statuses(),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date, $format = 'Y-m-d H:i:s')
    {
        return $date ? Carbon::parse($date)->format($format) : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
