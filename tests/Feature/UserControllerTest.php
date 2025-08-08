<?php

use App\Domains\Post\Models\Post;
use App\Domains\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

it('profile photo upload successfully', function () {
  Storage::fake('public');
  $user = User::factory()->create();
  $file = UploadedFile::fake()->image('photo.jpg', 100, 100);

  $token = JWTAuth::fromUser($user);

  $response = $this->withHeader('Authorization', "Bearer {$token}")
    ->post(route('user.profile'), [
      'photo' => $file,
    ]);

  $response->assertStatus(201);
  $this->assertDatabaseHas('files', [
    'model_id' => $user->id,
    'model_type' => User::class,
    'type' => 'profile',
  ]);
});

it('returns users sorted by their posts total views', function () {
  [$userA, $userB, $userC] = User::factory()->count(3)->create();

  [$postA, $postB, $postC] = Post::factory()
    ->sequence(
      ['user_id' => $userA->id],
      ['user_id' => $userB->id],
      ['user_id' => $userC->id],
    )
    ->count(3)
    ->create();

  $insertViews = function ($postId, $count, $ipPrefix) {
    collect(range(1, $count))->each(function ($i) use ($postId, $ipPrefix) {
      DB::table('views')->insert([
        'viewable_type' => Post::class,
        'viewable_id' => $postId,
        'user_id' => null,
        'ip_address' => "{$ipPrefix}{$i}",
        'user_agent' => 'PHPUnit',
        'viewed_at' => now(),
      ]);
    });
  };

  $insertViews($postA->id, 5, '10.0.0.');
  $insertViews($postB->id, 2, '10.0.1.');

  $token = JWTAuth::fromUser(User::factory()->create());

  $payload = $this->withHeader('Authorization', "Bearer {$token}")
    ->getJson(route('user.top_views'))
    ->assertStatus(200)
    ->json('data');

  $map = collect($payload)->keyBy('id');

  expect((int) ($map[$userA->id]['total_view_count'] ?? $map[$userA->id]['total_views']))->toBe(5)
    ->and((int) ($map[$userB->id]['total_view_count'] ?? $map[$userB->id]['total_views']))->toBe(2)
    ->and((int) ($map[$userC->id]['total_view_count'] ?? $map[$userC->id]['total_views']))->toBe(0);
});

