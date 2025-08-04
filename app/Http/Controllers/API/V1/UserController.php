<?php

namespace App\Http\Controllers\API\V1;

use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Services\FileService;
use App\Domains\User\Actions\UploadUserProfilePhotoAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\UploadProfileRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
  public function __construct(
    private UploadUserProfilePhotoAction $uploadAction
  ) {
  }

  /**
   * Handle uploading uesr profile photo
   *
   * @param \App\Http\Requests\UploadProfileRequest $request
   * @return JsonResponse
   */
  public function uploadProfile(UploadProfileRequest $request): JsonResponse
  {
    $user = $request->user();
    $this->uploadAction->execute($user, $request->file('photo'), );

    return $this->response(new UserResource($user));
  }
}