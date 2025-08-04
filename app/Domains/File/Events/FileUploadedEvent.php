<?php

namespace App\Domains\File\Events;

use App\Domains\File\DTOs\FileDTO;
use Illuminate\Foundation\Events\Dispatchable;

class FileUploadedEvent
{
  use Dispatchable;

  public function __construct(
    public FileDTO $file,
    public mixed $owner
  ) {
  }
}