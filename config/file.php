<?php

return [
  'disk' => env('FILE_STORAGE_DISK', 'public'),

  'directories' => [
    'default' => 'uploads',
    'profile' => 'users/profile_photos',
    'attachments' => 'attachments',
  ],
];