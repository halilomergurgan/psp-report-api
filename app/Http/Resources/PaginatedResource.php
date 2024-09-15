<?php
namespace App\Http\Resources;

use App\Enums\StatusEnum;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatedResource extends ResourceCollection
{
    protected $pagination;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->pagination = [
            'total' => $resource->total(),
            'count' => $resource->count(),
            'per_page' => $resource->perPage(),
            'current_page' => $resource->currentPage(),
            'total_pages' => $resource->lastPage(),
            'next_page_url' => $resource->nextPageUrl(),
            'prev_page_url' => $resource->previousPageUrl(),
        ];
    }

    public function toArray($request)
    {
        return [
            'status' => StatusEnum::APPROVED,
            'data' => $this->collection,
            'pagination' => $this->pagination,
        ];
    }
}
