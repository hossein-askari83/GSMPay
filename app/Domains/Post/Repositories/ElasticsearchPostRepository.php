<?php

namespace App\Domains\Post\Repositories;

use App\Domains\Post\DTOs\PostDTO;
use App\Domains\Post\Interfaces\PostRepositoryInterface;
use App\Domains\Post\Models\Post;
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
    /**
     * @inheritDoc
     */
    public function findAllPaginate(?int $perPage): LengthAwarePaginator
    {
        $page = request('page', 1);
        $results = $this->es->getPaginatedIndexData($this->indexName, $page, $perPage);
        $items = array_map(fn($data) => PostDto::fromArray($data['_source']), $results['data']);

        return new LengthAwarePaginator(
            $items,
            $results['total'],
            $perPage,
            $page,
            ['path' => request()->url()]
        );
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->es->getAllIndexData($this->indexName);
    }

    /**
     * @inheritDoc
     */
    public function findOne(int $id): ?PostDTO
    {
        $post = $this->es->getIndexData($this->indexName, $id);
        return $post ? PostDTO::fromArray($post['_source']) : null;
    }
}