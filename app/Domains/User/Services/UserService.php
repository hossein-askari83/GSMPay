<?php

namespace App\Domains\User\Services;

use App\Domains\User\Interfaces\UserRepositoryInterface;
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
  public function validateCredentials(string $mobile, string $password): ?object
  {
    $user = $this->repo->findByMobile($mobile);

    if ($user && Hash::check($password, $user->password)) {
      return $user;
    }

    return null;
  }
}
