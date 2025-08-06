<?php

namespace App\Domains\User\Services;

use App\Domains\User\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UserService
{
  public function __construct(private UserRepositoryInterface $repo)
  {
  }

  /**
   * Validate user credentials
   *
   * @param string $mobile
   * @param string $password
   * @return \App\Domains\User\Models\User|null
   */
  public function validateCredentials(string $mobile, string $password): ?User
  {
    $user = $this->repo->findByMobile($mobile);

    if ($user && Hash::check($password, $user->password)) {
      return $user;
    }

    return null;
  }

  public function getUsersSortedByPostViews(int $perPage = 20, int $page = 1): LengthAwarePaginator
  {
    $cacheKey = "users_sorted_by_views_page_$page";

    return Cache::remember($cacheKey, 60, function () use ($perPage) {
      return $this->repo->getUsersSortedByPostViews($perPage);
    });
  }
}
