<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginateRequest extends FormRequest
{
  public function rules(): array
  {
    return [
      'page' => ['integer', 'min:1'],
      'per_page' => ['integer', 'min:1', 'max:100'],
    ];
  }

  protected function prepareForValidation(): void
  {
    $this->merge(['per_page' => $this->input('per_page', 15)]);
  }
}