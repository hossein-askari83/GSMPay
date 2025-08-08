<?php

namespace App\Domains\Post\Repositories;

use App\Domains\Post\DTOs\PostDTO;
use App\Domains\Post\Interfaces\PostRepositoryInterface;
use App\Domains\Post\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentPostRepository implements PostRepositoryInterface
{

  /**
   * @inheritDoc
   */
  public function findAllPaginate(int $perPage): LengthAwarePaginator
  {
    return Post::with('user.profilePhoto')
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }

  /**
   * @inheritDoc
   */
  public function findAll(): array
  {
    return Post::with('user.profilePhoto', 'user.views')->get()->toArray();
  }

  /**
   * @inheritDoc
   */
  public function findOne(int $id): ?PostDTO
  {
    $post = Post::with('user.profilePhoto', 'user.views')->find($id);
    return $post ? PostDTO::fromModel($post) : null;
  }
}