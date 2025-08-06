<?php

namespace App\Console\Commands;

use App\Domains\Post\Repositories\EloquentPostRepository;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

class IndexPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Index all existing posts into Elasticsearch';

    /**
     * Execute the console command.
     */
    public function handle(
        EloquentPostRepository $repo,
        ElasticsearchService $service
    ) {
        if (!$service->isIndexExists("posts")) {
            $service->createIndex('posts');
        }
        $service->bulkIndexData('posts', $repo->findAll());
    }
}
