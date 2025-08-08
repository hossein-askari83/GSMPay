<?php

namespace App\Http\Controllers\API\V1;

use App\Domains\File\Enums\FileTypesEnum;
use App\Domains\File\Services\FileService;
use App\Domains\User\Actions\UploadUserProfilePhotoAction;
use App\Domains\User\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\PaginateRequest;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\UserResource;
use App\Http\Requests\UploadProfileRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
  public function __construct(
    private UploadUserProfilePhotoAction $uploadAction,
    private UserService $userService,
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

    return $this->response(new UserResource($user), Response::HTTP_CREATED);
  }

  /**
   * Return top 
   *
   * @param \App\Http\Requests\UploadProfileRequest $request
   * @return JsonResponse
   */
  public function topViewedUsers(PaginateRequest $request): JsonResponse
  {
    $users = $this->userService->getUsersSortedByPostViews($request->validated('per_page', 20), $request->validated('page', 1));

    return $this->response(UserResource::collection($users));
  }
}