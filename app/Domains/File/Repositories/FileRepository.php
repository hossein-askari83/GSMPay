<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\Interfaces\FileRepositoryInterface;
use App\Domains\File\Models\File;
use App\Domains\File\DTOs\FileDTO;

class FileRepository implements FileRepositoryInterface
{
  public function save(FileDTO $dto): FileDTO
  {
    $file = File::create($dto->toArray());
    return FileDTO::fromModel($file);
  }

  public function delete(int $fileId): void
  {
    File::findOrFail($fileId)->delete();
  }

  public function findOne(int $fileId): ?FileDTO
  {
    $file = File::find($fileId);
    return $file ? FileDTO::fromModel($file) : null;
  }
}