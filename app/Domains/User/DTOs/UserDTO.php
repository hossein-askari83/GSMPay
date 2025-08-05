<?php

namespace App\Domains\User\DTOs;

use App\Domains\File\DTOs\FileDTO;
use App\Domains\User\Models\User;


class UserDTO
{
  public function __construct(
    public ?int $id,
    public string $name,
    public string $mobile,
    public ?FileDTO $profilePhoto = null,
    public ?string $createdAt = null,
    public ?string $updatedAt = null,
    public ?string $deletedAt = null,
  ) {
  }

  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'name' => $this->name,
      'mobile' => $this->mobile,
      'profile_photo' => $this->profilePhoto,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
      'deleted_at' => $this->deletedAt,
    ];
  }

  public static function fromModel(User $user): self
  {
    return new self(
      $user->id,
      $user->name,
      $user->mobile,
      $user->relationLoaded('profilePhoto') && $user->profilePhoto
      ? FileDTO::fromModel($user->profilePhoto)
      : null,
      $user->created_at?->toIso8601String(),
      $user->updated_at?->toIso8601String(),
      $user->deleted_at?->toIso8601String(),
    );
  }

  public static function fromArray(array $data): self
  {
    return new self(
      $data['id'] ?? null,
      $data['name'],
      $data['mobile'],
      isset($data['profile_photo']) ? FileDTO::fromArray($data['profile_photo']) : null,
      $data['created_at'] ?? null,
      $data['updated_at'] ?? null,
      $data['deleted_at'] ?? null,
    );
  }
}
