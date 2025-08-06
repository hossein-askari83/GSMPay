<?php

namespace App\Domains\View\Interfaces;

use App\Domains\View\DTOs\ViewDTO;


interface ViewCounterInterface
{
  /**
   * Record a view for a Viewable model
   * @param \App\Domains\View\DTOs\ViewDTO $viewDTO
   * @return void
   */
  public function record(ViewDTO $viewDTO): void;
}