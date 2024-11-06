<?php

namespace App\Http\Requests\Property;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'city_id' => ['nullable', 'int'],
            'country_id' => ['nullable', 'int'],
            'geoobject' => ['nullable', 'string'],
            'adults' => ['nullable'],
            'children' => ['nullable', 'int'],
            'facilities' => 'nullable',
        ];
    }
}
