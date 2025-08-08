<?php

use App\Domains\Post\Actions\PostViewAction;
use App\Domains\Post\DTOs\PostDTO;
use App\Domains\Post\Models\Post;
use App\Domains\Post\Services\PostService;
use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tymon\JWTAuth\Facades\JWTAuth;


uses(RefreshDatabase::class);

it('returns paginated posts from the index endpoint', function () {
  $caller = User::factory()->create();
  $token = JWTAuth::fromUser($caller);

  $post = Post::factory()->make(['id' => 1, 'title' => 'Test']);

  $paginator = new LengthAwarePaginator(
    [$post],
    total: 1,
    perPage: 1,
    currentPage: 1,
  );

  $mockService = Mockery::mock(PostService::class);

  $mockService->shouldReceive('findAllPaginate')
    ->once()
    ->with(Mockery::any())
    ->andReturn($paginator);

  $this->instance(PostService::class, $mockService);

  $response = $this->withHeader('Authorization', "Bearer {$token}")
    ->getJson(route('post.index'));

  $response->assertStatus(200);
  $response->assertJsonStructure([
    'data' => [
      ['id', 'title', 'body', 'user']
    ],
    'server_time'
  ]);
});

it('shows a post successfully', function () {
  $post = Post::factory()->make([
    'id' => 10,
    'title' => 'Sample Post',
    'content' => 'This is a sample post content.',
    'user_id' => User::factory()->create()->id,
  ]);

  $postDTO = new PostDTO(
    id: $post->id,
    userId: $post->user_id,
    title: $post->title,
    body: $post->content,
    user: null,
    createdAt: now(),
    updatedAt: now()
  );

  $mockAction = Mockery::mock(PostViewAction::class);
  $mockAction->shouldReceive('execute')
    ->once()
    ->with($post->id, Mockery::type('string'))
    ->andReturn($postDTO);

  $this->app->instance(PostViewAction::class, $mockAction);

  $caller = User::factory()->create();
  $token = JWTAuth::fromUser($caller);

  $response = $this->withHeader('Authorization', "Bearer {$token}")->getJson(route('post.show', $post->id));


  $response->assertStatus(200)
    ->assertJsonFragment([
      'id' => $post->id,
      'title' => $post->title,
    ]);
});

it('returns 404 when post not found', function () {
  $mockAction = Mockery::mock(PostViewAction::class);
  $mockAction->shouldReceive('execute')
    ->once()
    ->with(1, Mockery::type('string'))
    ->andReturn(null);

  $this->app->instance(PostViewAction::class, $mockAction);

  $caller = User::factory()->create();
  $token = JWTAuth::fromUser($caller);
  $response = $this->withHeader('Authorization', "Bearer {$token}")->getJson(route('post.show', 1));

  $response->assertStatus(404)
    ->assertJsonFragment(['message' => 'Post not found']);
});