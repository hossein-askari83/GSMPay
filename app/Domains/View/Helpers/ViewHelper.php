<?php

namespace App\Domains\View\Helpers;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use \App\Domains\View\Interfaces\ViewCounterInterface;

/**
 * Helper class for view-related operations, such as recording views on entities.
 *
 * @package App\Domains\View\Helpers
 */
class ViewHelper
{
  /**
   * Record a view event for a viewable entity.
   *
   * This method resolves the ViewCounterInterface implementation from the container
   * and delegates the recording of the view event using the provided ViewDTO.
   *
   * @param ViewDTO $viewDTO The data transfer object containing view information
   * @return void
   */
  public static function recordView(ViewDTO $viewDTO): void
  {
    /** @var ViewCounterInterface $counter */
    $counter = app(ViewCounterInterface::class);
    $counter->record($viewDTO);
  }
}