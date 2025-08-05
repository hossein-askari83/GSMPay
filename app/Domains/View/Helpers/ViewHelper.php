<?php

namespace App\Domains\View\Helpers;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use \App\Domains\View\Interfaces\ViewCounterInterface;

class ViewHelper
{
  public static function recordView(ViewDTO $viewDTO): void
  {
    /** @var ViewCounterInterface $counter */
    $counter = app(ViewCounterInterface::class);
    $counter->record($viewDTO);
  }
}