<?php

namespace App\Domains\File\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
  protected $fillable = [
    'disk',
    'path',
    'mime_type',
    'size',
    'model_type',
    'model_id',
    'type',
  ];

  protected $casts = [
    'type' => \App\Domains\File\Enums\FileTypesEnum::class,
  ];

  public function model(): MorphTo
  {
    return $this->morphTo();
  }
}