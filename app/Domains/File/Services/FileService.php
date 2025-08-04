<?php

namespace App\Domains\File\Services;

use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Events\FileUploadedEvent;
use App\Domains\File\DTOs\FileDTO;
use App\Domains\File\Interfaces\FileOwnerInterface;
use App\Domains\File\Interfaces\FileRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileService
{

  protected string $disk;

  protected string $directory;

  public function __construct(
    private FileRepositoryInterface $repo
  ) {

  }

  public function upload(UploadedFile $file, FileOwnerInterface $owner, FileTypesEnum $type): FileDTO
  {
    $disk = $owner->getStorageDisk();
    $directory = $owner->getStorageDirectory();
    $filename = Str::uuid() . '.' . $file->extension();
    $path = Storage::disk($disk)->putFileAs($directory, $file, $filename);
    $dto = new FileDTO(
      id: null,
      disk: $disk,
      path: $path,
      mimeType: $file->getMimeType(),
      size: $file->getSize(),
      modelId: $owner->getFileKey(),
      modelType: get_class($owner),
      type: $type
    );

    $saved = $this->repo->save($dto);
    event(new FileUploadedEvent($saved, $owner));

    return $saved;
  }

  public function delete(int $fileId): void
  {
    $file = $this->repo->findOne($fileId);
    if ($file) {
      Storage::disk($file->disk)->delete($file->path);
      $this->repo->delete($fileId);
    }
  }
}