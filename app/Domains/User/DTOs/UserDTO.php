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
    public ?int $totalViews = 0,
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
      'total_views'=>$this->totalViews
    ];
  }

  public static function fromModel(User $user): self
  {
    return new self(
      id: $user->id,
      name: $user->name,
      mobile: $user->mobile,
      profilePhoto: $user->relationLoaded('profilePhoto') && $user->profilePhoto
      ? FileDTO::fromModel($user->profilePhoto)
      : null,
      totalViews: $user->viewsCount(),
      createdAt: $user->updated_at?->toIso8601String(),
      updatedAt: $user->deleted_at?->toIso8601String(),
    );
  }

  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      name: $data['name'],
      mobile: $data['mobile'],
      profilePhoto: isset($data['profile_photo']) ? FileDTO::fromArray($data['profile_photo']) : null,
      totalViews: $data['total_views']??count($data['views']) ?? 0,
      createdAt: $data['updated_at'] ?? null,
      updatedAt: $data['deleted_at'] ?? null,
    );
  }
}
