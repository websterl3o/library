<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
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
            'title' => 'required|string|max:40',
            'publisher' => 'required|string|max:40',
            'edition' => 'required|integer|min:1',
            'publication_year' => 'required|string|size:4',
            'price' => 'required|numeric|min:0',
            'authors' => 'required|array|min:1',
            'authors.*' => 'exists:authors,id',
            'subjects' => 'required|array|min:1',
            'subjects.*' => 'exists:subjects,id',
        ];
    }
}
