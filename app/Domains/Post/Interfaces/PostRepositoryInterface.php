<?php

namespace App\Domains\Post\Interfaces;

use App\Domains\Post\DTOs\PostDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \App\Domains\Post\Models\Post;

interface PostRepositoryInterface
{
    /**
     * List paginated posts
     * 
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function findAllPaginate(int $perPage): LengthAwarePaginator;

    /**
     * List all posts
     * 
     * @return array
     */
    public function findAll(): array;


    /**
     * Find a post based on given id
     * @param int $id
     * @return ?\App\Domains\Post\DTOs\PostDTO
     */
    public function findOne(int $id): ?PostDTO;
}