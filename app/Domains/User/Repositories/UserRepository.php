<?php

namespace App\Domains\User\Repositories;

use App\Domains\Post\Models\Post;
use App\Domains\User\DTOs\UserDTO;
use App\Domains\User\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserRepositoryInterface
{

  /**
   * @inheritDoc
   */
  public function findByMobile(string $mobile): ?User
  {
    return User::where('mobile', $mobile)->first();
  }

  /**
   * @inheritDoc
   */
  public function getUsersSortedByPostViews(int $perPage = 20): LengthAwarePaginator
  {
    // return User::query()
    //   ->select([
    //     'users.id',
    //     'users.name',
    //     'users.mobile',
    //     DB::raw('COALESCE(COUNT(views.id), 0) as total_views'),
    //   ])
    //   ->leftJoin('posts', 'posts.user_id', '=', 'users.id')
    //   ->leftJoin('views', function ($join) {
    //     $join->on('views.viewable_id', '=', 'posts.id')
    //       ->where('views.viewable_type', Post::class);
    //   })
    //   ->groupBy('users.id', 'users.name', 'users.mobile')
    //   ->orderByDesc('total_views')
    //   ->with('profilePhoto')
    //   ->paginate($perPage);

    return User::with('profilePhoto')
      ->withCount('views')->orderByDesc('views_count')
      ->paginate($perPage);

  }
}
