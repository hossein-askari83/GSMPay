<?php

namespace App\Domains\View\Traits;

use App\Domains\View\Models\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Domains\View\Interfaces\ViewCounterInterface;


trait HasViews
{
  public function views(): MorphMany
  {
    return $this->morphMany(View::class, 'viewable');
  }

  public function viewCount(): int
  {
    return $this->views()->count();
  }
}