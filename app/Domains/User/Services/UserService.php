<?php

namespace App\Domains\User\Services;

use App\Domains\User\DTOs\UserDTO;
use App\Domains\User\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\User;
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
}
