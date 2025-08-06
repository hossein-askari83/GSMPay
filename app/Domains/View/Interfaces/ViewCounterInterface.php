<?php

namespace App\Domains\View\Interfaces;

use App\Domains\View\DTOs\ViewDTO;

/**
 * Interface for recording views on viewable models.
 *
 * Provides a contract for recording view events using a ViewDTO.
 *
 * @package App\Domains\View\Interfaces
 */
interface ViewCounterInterface
{
  /**
   * Record a view for a viewable model.
   *
   * @param ViewDTO $viewDTO The data transfer object containing view information
   * @return void
   */
  public function record(ViewDTO $viewDTO): void;
}