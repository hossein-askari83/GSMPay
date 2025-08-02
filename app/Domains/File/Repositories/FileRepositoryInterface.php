<?php

namespace App\Domains\File\Repositories;

use App\Domains\File\DTOs\FileDTO;

interface FileRepositoryInterface
{
  public function save(FileDTO $dto): FileDTO;
  public function delete(int $fileId): void;
}