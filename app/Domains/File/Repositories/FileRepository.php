<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\Interfaces\FileRepositoryInterface;
use App\Domains\File\Models\File;
use App\Domains\File\DTOs\FileDTO;

class FileRepository implements FileRepositoryInterface
{
  public function save(FileDTO $dto): FileDTO
  {
    $File = File::create($dto->toArray());
    return FileDTO::fromModel($File);
  }

  public function delete(int $fileId): void
  {
    File::findOrFail($fileId)->delete();
  }

  public function findOne(int $fileId): ?FileDTO
  {
    if ($file = File::find($fileId)) {
      return FileDTO::fromModel($file);
    }
    return null;
  }
}