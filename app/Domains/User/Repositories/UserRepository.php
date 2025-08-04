<?php

namespace App\Domains\User\Repositories;

use App\Domains\User\Interfaces\UserRepositoryInterface;
use App\Domains\User\Models\User;

class UserRepository implements UserRepositoryInterface
{

  /**
   * @inheritDoc
   */
  public function findByMobile(string $mobile): ?User
  {
    return User::where('mobile', $mobile)->first();
  }
}
