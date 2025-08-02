<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadProfilePhotoRequest;
use App\Domains\File\Actions\UploadFileAction;
use App\Domains\User\Resources\UserResource;
use App\Http\Requests\UploadProfileRequest;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
  public function __construct(
    private UploadFileAction $uploadAction
  ) {
  }

  /**
   * Handle uploading uesr profile photo
   *
   * @param \App\Http\Requests\UploadProfileRequest $request
   * @return \Illuminate\Http\JsonResponse
   */
  public function uploadProfile(UploadProfileRequest $request): JsonResponse
  {
    $user = $request->user();
    $fileDto = $this->uploadAction->execute($request->file('photo'), $user);

    // attach to user
    $user->profile_file_id = $fileDto->id;
    $user->save();

    return response()->json([
      'user' => new UserResource($user),
    ]);
  }
}