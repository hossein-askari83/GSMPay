<?php

namespace App\Domains\Post\Interfaces;

use App\Domains\Post\DTOs\PostDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use \App\Domains\Post\Models\Post;

/**
 * Interface for post repository operations.
 * 
 * This interface defines the contract for post data access operations,
 * providing methods to retrieve posts with pagination support and find
 * individual posts by ID. It abstracts the data access layer for posts,
 * allowing for different implementations (e.g., Eloquent, Elasticsearch).
 * 
 * @package App\Domains\Post\Interfaces
 */
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