<?php

namespace App\Domains\View\Traits;

use App\Domains\View\Models\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Domains\View\Interfaces\ViewCounterInterface;

/**
 * Trait providing view tracking capabilities to Eloquent models.
 *
 * Implements ViewableInterface methods for accessing related views and retrieving
 * view counts. Use this trait on models that need view tracking functionality.
 *
 * @trait
 * @package App\Domains\View\Traits
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasViews
{
  /**
   * Get the morphMany relationship for views associated with this model.
   *
   * @return MorphMany
   */
  public function views(): MorphMany
  {
    return $this->morphMany(View::class, 'viewable');
  }

  /**
   * Get the total number of views for this model.
   *
   * @return int
   */
  public function viewsCount(): int
  {
    return $this->views()->count();
  }

  public function getViewableKey(): int
  {
    return $this->id;
  }
}