<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'image' => ['required', 'url'],
            'published_at' => ['required', 'date'],

            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],

            'media' => ['nullable', 'array'],
            'media.*' => ['nullable', 'url'],
        ];
    }
}
