<?php

namespace App\Console\Commands;

use App\Domains\Post\Repositories\EloquentPostRepository;
use App\Services\ElasticsearchService;
use Illuminate\Console\Command;

/**
 * Console command for indexing posts into Elasticsearch.
 * 
 * This command retrieves all existing posts from the database and indexes them
 * into Elasticsearch for improved search functionality. It automatically creates
 * the "posts" index if it doesn't exist before performing the bulk indexing
 * operation.
 * 
 * The command uses the EloquentPostRepository to fetch all posts and the
 * ElasticsearchService to handle the indexing operations, including index
 * creation and bulk data insertion.
 * 
 * Usage:
 * php artisan posts:index
 * 
 * @package App\Console\Commands
 */
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
