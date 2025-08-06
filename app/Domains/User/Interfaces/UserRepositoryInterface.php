<?php

namespace App\Domains\User\Interfaces;

use App\Domains\User\DTOs\UserDTO;
use App\Domains\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for user repository operations, providing user lookup and ranking functionality.
 */
interface UserRepositoryInterface
{
  /**
   * Find a user by mobile number.
   * 
   * Returns the user model instance directly for authentication purposes.
   *
   * @param string $mobile The mobile number to search for
   * @return User|null Returns the user if found, null otherwise
   */
  public function findByMobile(string $mobile): ?User;

  /**
   * Get users sorted by their post view counts.
   * 
   * @param int $perPage Number of users per page (default: 20)
   * @return LengthAwarePaginator Paginated list of users sorted by post views
   */
  public function getUsersSortedByPostViews(int $perPage = 20): LengthAwarePaginator;
}