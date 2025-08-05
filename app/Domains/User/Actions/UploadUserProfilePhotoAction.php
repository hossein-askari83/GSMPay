<?php

namespace App\Domains\User\Actions;

use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Services\FileService;
use App\Domains\User\Models\User;
use Illuminate\Http\UploadedFile;

class UploadUserProfilePhotoAction
{
  public function __construct(
    protected FileService $fileService
  ) {
  }

  public function execute(User $user, UploadedFile $file): void
  {
    if ($user->profilePhoto) {
      $this->fileService->delete($user->profilePhoto->id);
    }

    $this->fileService->upload($file, $user, FileTypesEnum::PROFILE);
  }
}
