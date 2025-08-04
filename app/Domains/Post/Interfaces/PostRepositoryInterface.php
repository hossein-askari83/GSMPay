<?php

namespace App\Domains\Post\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    /**
     * List paginated posts from ES or DB
     * 
     * @param int \$perPage
     * @return LengthAwarePaginator
     */
    public function findAllPaginate(int $perPage): LengthAwarePaginator;

    public function findAll(): array;
}