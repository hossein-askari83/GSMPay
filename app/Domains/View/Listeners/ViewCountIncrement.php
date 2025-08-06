<?php

namespace App\Domains\View\Listeners;

use App\Domains\View\Contracts\ViewCounter;
use App\Domains\View\Events\ViewableViewed;
use App\Domains\View\Helpers\ViewHelper;

class ViewCountIncrement
{
  public function handle(ViewableViewed $event): void
  {
    ViewHelper::recordView($event->viewDTO);
  }
}
