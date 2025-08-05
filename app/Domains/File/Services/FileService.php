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
  public function __construct(
    private FileRepositoryInterface $repo
  ) {
  }

  /**
   * Upload file on relative disk at file type path
   * @param \Illuminate\Http\UploadedFile $file
   * @param \App\Domains\File\Interfaces\FileOwnerInterface $owner
   * @param \App\Domains\File\Enums\FileTypesEnum $type
   * @return FileDTO
   */
  public function upload(UploadedFile $file, FileOwnerInterface $owner, FileTypesEnum $type): FileDTO
  {
    $disk = $owner->getStorageDisk();
    $filename = Str::uuid() . '.' . $file->extension();
    $path = Storage::disk($disk)->putFileAs($type->value, $file, $filename);
    $dto = new FileDTO(
      id: null,
      disk: $disk,
      path: $path,
      origianlName: $file->getClientOriginalName(),
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

  /**
   * Remove file and it's record on database
   * @param int $fileId
   * @return void
   */
  public function delete(int $fileId): void
  {
    $file = $this->repo->findOne($fileId);
    if ($file) {
      Storage::disk($file->disk)->delete($file->path);
      $this->repo->delete($fileId);
    }
  }
}