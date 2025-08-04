<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'title' => $this->title,
      'body' => $this->body,
      'user' => new UserResource($this->user),
      'created_at' => "SD",
    ];
  }
}