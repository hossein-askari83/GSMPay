<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Domains\File\Models\File;
use App\Domains\File\DTOs\FileDTO;

class FileRepository implements FileRepositoryInterface
{
  public function save(FileDTO $dto): FileDTO
  {
    // dd($dto->toArray());
    $File = File::create($dto->toArray());
    return FileDTO::fromModel($File);
  }

  public function delete(int $fileId): void
  {
    File::findOrFail($fileId)->delete();
  }
}