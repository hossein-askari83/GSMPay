<?php

namespace App\Domains\Post\Actions;

use App\Domains\Post\DTOs\PostDTO;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\PostService;
use App\Domains\View\DTOs\ViewDTO;
use App\Domains\View\Events\ViewableViewed;
use Illuminate\Http\Request;

class PostViewAction
{
  public function __construct(
    protected PostService $postService
  ) {
  }

  public function execute(int $postId, string $ipAddress): ?PostDTO
  {
    $postDTO = $this->postService->findById($postId);
    if ($postDTO) {
      $viewData = [
        'viewable_type' => Post::class,
        'viewable_id' => $postDTO->id,
        'ip_address' => $ipAddress,
        'user_id' => auth()->id(),
        'user_agent' => request()->userAgent(),
        'viewed_at' => now()->toIso8601String(),
      ];
      event(new ViewableViewed(ViewDTO::fromArray($viewData)));
    }
    return $postDTO;
  }
}