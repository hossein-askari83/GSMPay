<?php

namespace App\Domains\View\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class View extends Model
{
  public $timestamps = false;
  protected $fillable = [
    'viewable_type',
    'viewable_id',
    'user_id',
    'ip_address',
    'user_agent',
    'viewed_at',
  ];

  public function viewable(): MorphTo
  {
    return $this->morphTo();
  }
}
