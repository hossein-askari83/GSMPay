<?php

namespace App\Http\Controllers\API\V1;

use App\Domains\Post\Services\PostService;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Http\Requests\PaginateRequest;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use stdClass;
use function Pest\Laravel\instance;

class PostController extends Controller
{
  public function __construct(private PostService $service)
  {
  }

  public function index(PaginateRequest $request)
  {
    $paginated = $this->service->findAllPaginate($request->per_page);
    if ($paginated->first() instanceof stdClass) {
      return $paginated;
    }
    return PostResource::collection($paginated);
  }
}