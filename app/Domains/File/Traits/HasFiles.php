<?php

namespace App\Domains\File\Traits;

use App\Domains\File\DTOs\FileDTO;
use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Models\File;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait providing file management capabilities to Eloquent models.
 *
 * The HasFiles trait adds methods for grouping files by type, accessing a profile photo,
 * and determining the storage disk. Intended for use with models that have a polymorphic
 * relationship to files, it simplifies file retrieval and organization.
 *
 * @trait
 * @package App\Domains\File\Traits
 * @mixin \Illuminate\Database\Eloquent\Model
 */
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