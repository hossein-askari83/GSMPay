<?php

namespace App\Domains\File\Traits;

use App\Domains\File\DTOs\FileDTO;
use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphOne;
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

  /**
   * A single profile photo of this owner
   */
  public function profilePhoto(): MorphOne
  {
    return $this->morphOne(File::class, 'model')
      ->where('type', FileTypesEnum::PROFILE->value);
  }

  public function getStorageDisk(): string
  {
    return config('filesystems.default');
  }
}