<?php

namespace App\Domains\View\Events;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ViewableViewed
{
  use Dispatchable, SerializesModels;

  public function __construct(public ViewDTO $viewDTO)
  {
  }
}