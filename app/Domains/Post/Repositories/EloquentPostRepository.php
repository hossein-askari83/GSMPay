<?php

namespace App\Domains\Post\Repositories;

use App\Domains\Post\Interfaces\PostRepositoryInterface;
use App\Domains\Post\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPostRepository implements PostRepositoryInterface
{
  public function findAllPaginate(int $perPage): LengthAwarePaginator
  {
    return Post::with('user.profilePhoto')
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }

  public function findAll(): array
  {
    return Post::with('user.profilePhoto')->get()->toArray();
  }
}