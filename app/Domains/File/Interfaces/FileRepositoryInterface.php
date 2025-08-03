<?php

namespace App\Domains\File\Interfaces;

use App\Domains\File\DTOs\FileDTO;

interface FileRepositoryInterface
{
  public function findOne(int $id): ?FileDTO;
  public function save(FileDTO $dto): FileDTO;
  public function delete(int $fileId): void;
}