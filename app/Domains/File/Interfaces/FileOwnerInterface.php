<?php

namespace App\Domains\File\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;


interface FileOwnerInterface
{

  public function getFileKey(): int|string;

  public function files(): MorphMany;
  public function getGroupedFiles(): array;

  public function getStorageDisk(): string;
}