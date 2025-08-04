<?php

namespace App\Domains\Post\DTOs;

use App\Domains\Post\Models\Post;
use App\Domains\User\DTOs\UserDTO;

class PostDTO
{
  public function __construct(
    public ?int $id,
    public int $userId,
    public string $title,
    public string $body,
    public ?UserDTO $user = null,
    public string $createdAt,
    public string $updatedAt,
  ) {
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'user_id' => $this->userId,
      'title' => $this->title,
      'body' => $this->body,
      'user' => $this->user?->toArray(),
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
    ];
  }

  public static function fromModel(Post $post): self
  {
    return new self(
      $post->id,
      $post->user_id,
      $post->title,
      $post->body,
      isset($post->user) ? UserDTO::fromModel($post->user) : null,
      $post->created_at->toIso8601String(),
      $post->updated_at->toIso8601String(),
    );
  }

  public static function fromArray(array $data): self
  {
    return new self(
      $data['id'] ?? null,
      $data['user_id'],
      $data['title'],
      $data['body'],
      isset($data['user']) ? UserDTO::fromArray($data['user']) : null,
      $data['created_at'],
      $data['updated_at'],
    );
  }
}
