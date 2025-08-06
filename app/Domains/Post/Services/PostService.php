<?php

namespace App\Domains\Post\Services;

use App\Domains\Post\DTOs\PostDTO;
use App\Domains\Post\Interfaces\PostRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{
  public function __construct(
    private PostRepositoryInterface $repo
  ) {
  }

  public function findAllPaginate(int $perPage): LengthAwarePaginator
  {
    return $this->repo->findAllPaginate($perPage);
  }
  public function findById(int $id): ?PostDTO
  {
    return $this->repo->findOne($id);
  }
}