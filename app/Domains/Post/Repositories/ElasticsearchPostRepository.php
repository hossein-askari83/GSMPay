<?php

namespace App\Domains\Post\Repositories;

use App\Domains\Post\Interfaces\PostRepositoryInterface;
use App\Services\ElasticsearchService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ElasticsearchPostRepository implements PostRepositoryInterface
{
    private $indexName;
    public function __construct(private ElasticsearchService $es)
    {
        $this->indexName = "posts";
    }

    public function findAllPaginate(?int $perPage): LengthAwarePaginator
    {
        $page = request('page', 1);
        $results = $this->es->getPaginatedIndexData($this->indexName, $page, $perPage);
        $items = array_map(fn($data) => (object) $data['_source'], $results['data']);

        return new LengthAwarePaginator(
            $items,
            $results['total'],
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    public function findAll(): array
    {
        return $this->es->getAllIndexData($this->indexName);
    }
}