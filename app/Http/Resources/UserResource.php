<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'mobile' => $this->mobile,
      'profile_photo_url' => $this->profilePhoto
        ? asset("storage/{$this->profilePhoto->path}")
        : null,
    ];
  }
}
