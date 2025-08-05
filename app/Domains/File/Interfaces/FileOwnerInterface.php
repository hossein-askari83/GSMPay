<?php

namespace App\Domains\File\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;


interface FileOwnerInterface
{

  /**
   * Return proper key for save owner
   * @return int|string
   */
  public function getFileKey(): int|string;

  public function files(): MorphMany;

  /**
   * Return owner files grouped by file types
   * @return array
   */
  public function getGroupedFiles(): array;

  public function getStorageDisk(): string;
}