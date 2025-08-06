<?php

namespace App\Http\Controllers\API\V1;

use App\Domains\Post\Actions\PostViewAction;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\PostService;
use App\Domains\View\Enums\ViewableEntitiesEnum;
use App\Domains\View\Events\ViewableViewed;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralResource;
use App\Http\Resources\PostResource;
use App\Http\Requests\PaginateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use stdClass;
use Symfony\Component\HttpFoundation\Response;
use function Pest\Laravel\instance;

class PostController extends Controller
{
  public function __construct(private PostService $service, private PostViewAction $postViewAction)
  {
  }

  public function index(PaginateRequest $request): JsonResponse
  {
    $paginated = $this->service->findAllPaginate($request->per_page);

    return $this->response(PostResource::collection($paginated));
  }

  public function show(int $postId, Request $request): JsonResponse
  {
    $postDTO = $this->postViewAction->execute($postId, $request->ip());
    if ($postDTO) {
      return $this->response(new PostResource($postDTO));
    }
    return $this->response(['message' => "Post not found"], Response::HTTP_NOT_FOUND);
  }
}