<?php

namespace App\Domains\File\Actions;

use App\Domains\File\Services\FileService;
use Illuminate\Http\UploadedFile;

class UploadFileAction
{
  public function __construct(
    private FileService $service
  ) {
  }

  public function execute(UploadedFile $file, mixed $owner)
  {
    return $this->service->upload($file, $owner);
  }
}