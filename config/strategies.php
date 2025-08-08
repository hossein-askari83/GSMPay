<?php

return [
  'view_counter_driver' => env('VIEW_COUNTER_DRIVER', 'kafka'),

  'view_counter_map' => [
    'database' => \App\Domains\View\Services\ViewCounter::class,
    'kafka' => \App\Domains\View\Services\KafkaViewCounter::class,
  ],

  'post_repository_driver' => env('POST_REPOSITORY_DRIVER', 'elasticsearch'),

  'post_repository_map' => [
    'eloquent' => \App\Domains\Post\Repositories\EloquentPostRepository::class,
    'elasticsearch' => \App\Domains\Post\Repositories\ElasticsearchPostRepository::class,
  ]
];