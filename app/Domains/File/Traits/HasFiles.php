<?php

namespace App\Domains\File\Traits;

use App\Domains\File\DTOs\FileDTO;
use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Models\File;
trait HasFiles
{
  public function getGroupedFiles(): array
  {
    $grouped = $this->files()
      ->get()
      ->groupBy(fn(File $f) => $f->type);

    return array_map(
      fn($collection) => $collection->map(fn($m) => FileDTO::fromModel($m)),
      $grouped->toArray()
    );
  }

  public function getProfile(): ?FileDTO
  {
    $profile = $this->files()
      ->where('type', FileTypesEnum::PROFILE->value)
      ->latest()
      ->first();

    return $profile ? FileDTO::fromModel($profile) : null;
  }

  public function getStorageDisk(): string
  {
    return config('file.disk');
  }
  public function getStorageDirectory(): string
  {
    return config('file.directories.default');
  }
}