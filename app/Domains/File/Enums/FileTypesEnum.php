<?php

namespace App\Domains\File\Enums;


enum FileTypesEnum: string
{
  case PROFILE = 'profile';
  case ATTACHMENT = 'attachment';
  case POST_IMAGE = 'post_image';

  public static function toArray(): array
  {
    return array_column(self::cases(), 'value');
  }
}