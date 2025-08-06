<?php

namespace App\Domains\View\Repositories;

use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Models\View;

class ViewRepository
{
  public function save(ViewDTO $dto): ViewDTO
  {
    $view = View::create($dto->toArray());
    return ViewDTO::fromModel($view);
  }
}