<?php

namespace App\Http\Requests;

use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest
{
    use ApiResponse;

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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'published' => ['required', 'boolean'],
            'slug' => ['required', 'string', 'unique:posts,slug'],
            'content' => ['required', 'string'],
            'image' => ['required', 'url'],
            'published_at' => ['required', 'date'],

            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'integer', 'exists:categories,id'],

            'media' => ['nullable', 'array'],
            'media.*' => ['nullable', 'url'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            $this->validationError($validator)
        );
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
            'published' => true,
        ]);
    }
}
