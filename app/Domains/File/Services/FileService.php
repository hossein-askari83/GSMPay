<?php

namespace App\Domains\File\Services;

use App\Domains\File\Events\FileUploadedEvent;
use App\Domains\File\Repositories\FileRepositoryInterface;
use App\Domains\File\DTOs\FileDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
  public function __construct(
    private FileRepositoryInterface $repo
  ) {
  }

  public function upload(UploadedFile $file, mixed $owner): FileDTO
  {
    $disk = 'public';
    $directory = strtolower(class_basename($owner)) . '/' . $owner->id;
    $filename = now()->timestamp . '.' . $file->extension();
    $path = Storage::disk($disk)->putFileAs($directory, $file, $filename);
    $dto = new FileDTO(
      null,
      disk: $disk,
      path: $path,
      mimeType: $file->getMimeType(),
      size: $file->getSize(),
      modelId: $owner->id,
      modelType: get_class($owner)
    );

    $saved = $this->repo->save($dto);
    event(new FileUploadedEvent($saved, $owner));

    return $saved;
  }

  public function delete(int $fileId): void
  {
    $this->repo->delete($fileId);
  }
}