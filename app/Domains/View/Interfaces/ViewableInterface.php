<?php

namespace App\Domains\View\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @method MorphMnay views
 * @method void recordView
 * @method int viewCount
 */
interface ViewableInterface
{
  /**
   * Return viewable views
   * @return MorphMany
   */
  public function views(): MorphMany;

  /**
   * Retrieve total view count
   * @return int
   */
  public function viewCount(): int;

  /**
   * Return proper key to save viewable
   * @return int
   */
  public function getViewableKey(): int;
}

