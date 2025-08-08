<?php

namespace App\Domains\View\Interfaces;

use Illuminate\Database\Eloquent\Relations\Relation;

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
   * @return \Illuminate\Database\Eloquent\Relations\Relation
   */
  public function views(): Relation;

  /**
   * Retrieve the total view count for this entity.
   *
   * @return int
   */
  public function viewsCount(): int;

  /**
   * Get the unique key for this viewable entity.
   *
   * @return int
   */
  public function getViewableKey(): int;
}

