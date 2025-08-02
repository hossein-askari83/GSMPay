<?php

namespace App\Domains\File\Listeners;

use App\Domains\File\Events\FileUploadedEvent;
use Illuminate\Support\Facades\Storage;

class FileVirusScan
{
  public function handle(FileUploadedEvent $event): void
  {
    //Scan file for virus detection
  }
}