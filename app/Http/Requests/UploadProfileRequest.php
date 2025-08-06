<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadProfileRequest extends FormRequest
{
  public function authorize(): bool
  {
    return $this->user() !== null;
  }

  public function rules(): array
  {
    return [
      'photo' => [
        'required',
        'file',
        'mimes:jpg,jpeg,png,gif',
        'max:2048',  // max 2MB
      ],
    ];
  }

  public function messages(): array
  {
    return [
      'photo.required' => 'A profile photo is required.',
      'photo.file' => 'The upload must be a file.',
      'photo.mimes' => 'Allowed file types: jpg, jpeg, png, gif.',
      'photo.max' => 'Maximum file size is 2MB.',
    ];
  }
}