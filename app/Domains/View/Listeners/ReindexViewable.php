<?php

namespace App\Domains\View\Listeners;

use App\Domains\Post\Models\Post;
use App\Domains\Post\Repositories\EloquentPostRepository;
use App\Domains\View\Events\ViewableViewed;
use App\Services\ElasticsearchService;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReindexViewable
{

  public function __construct(
    private ElasticsearchService $service,
    private EloquentPostRepository $repo
  ) {
  }

  public function handle(ViewableViewed $event)
  {
    $viewDTO = $event->viewDTO;
    if ($viewDTO->viewableType === Post::class) {
      $postId = $viewDTO->viewableId;
      $post = $this->repo->findOne($postId);
      
      if (!$this->service->isIndexExists("posts")) {
        $this->service->createIndex('posts');
      }
      $this->service->bulkIndexData('posts', [$post->toArray()]);
    }
  }
}