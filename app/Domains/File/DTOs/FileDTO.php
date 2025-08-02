<?php

namespace App\Domains\File\DTOs;

use App\Domains\File\Models\File;

class FileDTO
{
  public function __construct(
    public ?int $id,
    public string $disk,
    public string $path,
    public string $mimeType,
    public int $size,
    public int $modelId,
    public string $modelType
  ) {
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'disk' => $this->disk,
      'path' => $this->path,
      'mime_type' => $this->mimeType,
      'size' => $this->size,
      'model_id' => $this->modelId,
      'model_type' => $this->modelType,
    ];
  }

  public static function fromModel(File $file): self
  {
    return new self(
      $file->id,
      $file->disk,
      $file->path,
      $file->mime_type,
      $file->size,
      $file->model_id,
      $file->model_type
    );
  }
}