<?php

namespace App\Domains\User\Interfaces;

use App\Domains\User\DTOs\UserDTO;
use App\Domains\User\Models\User;

interface UserRepositoryInterface
{
  /**
   * Find a user by mobile number (return an user instance itself due to auth functionalities)
   *
   * @param string $mobile
   * @return User|null
   */
  public function findByMobile(string $mobile): ?User;
}