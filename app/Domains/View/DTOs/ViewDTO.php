<?php

namespace App\Domains\View\DTOs;

use App\Domains\User\DTOs\UserDTO;
use App\Domains\View\Models\View;

class ViewDTO
{
  public function __construct(
    public ?int $id,
    public string $viewableType,
    public int $viewableId,
    public ?int $userId,
    public ?UserDTO $user,
    public ?string $ipAddress,
    public ?string $userAgent,
    public ?string $viewedAt
  ) {
  }

  /**
   * Convert the DTO to an array, suitable for API responses or Kafka messages.
   *
   * @return array
   */
  public function toArray(): array
  {
    return [
      'id' => $this->id,
      'viewable_type' => $this->viewableType,
      'viewable_id' => $this->viewableId,
      'user_id' => $this->userId,
      'user' => $this->user?->toArray(),
      'ip_address' => $this->ipAddress,
      'user_agent' => $this->userAgent,
      'viewed_at' => $this->viewedAt,
    ];
  }

  /**
   * Create a ViewDTO from a View model instance.
   *
   * @param View $view
   * @return self
   */
  public static function fromModel(View $view): self
  {
    return new self(
      id: $view->id,
      viewableType: $view->viewable_type,
      viewableId: $view->viewable_id,
      userId: $view->user_id,
      user: $view->user ? UserDTO::fromModel($view->user) : null,
      ipAddress: $view->ip_address,
      userAgent: $view->user_agent,
      viewedAt: $view->viewed_at
    );
  }

  /**
   * Create a ViewDTO from an array, e.g., from a Kafka message.
   *
   * @param array $data
   * @return self
   */
  public static function fromArray(array $data): self
  {
    return new self(
      id: $data['id'] ?? null,
      viewableType: $data['viewable_type'],
      viewableId: $data['viewable_id'],
      userId: $data['user_id'] ?? null,
      user: isset($data['user']) && is_array($data['user']) ? UserDTO::fromArray($data['user']) : null,
      ipAddress: $data['ip_address'] ?? null,
      userAgent: $data['user_agent'] ?? null,
      viewedAt: $data['viewed_at'] ?? null
    );
  }
}