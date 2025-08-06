<?php

namespace App\Domains\View\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Interface for models that can be viewed and have view tracking.
 *
 * Provides methods for accessing related views, retrieving view counts,
 * and obtaining the unique key for the viewable entity.
 *
 * @package App\Domains\View\Interfaces
 */
interface ViewableInterface
{
  /**
   * Get the morphMany relationship for views associated with this entity.
   *
   * @return MorphMany
   */
  public function views(): MorphMany;

  /**
   * Retrieve the total view count for this entity.
   *
   * @return int
   */
  public function viewCount(): int;

  /**
   * Get the unique key for this viewable entity.
   *
   * @return int
   */
  public function getViewableKey(): int;
}

