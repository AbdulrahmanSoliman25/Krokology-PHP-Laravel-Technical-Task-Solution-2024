<?php

namespace App\Http\Requests\Todo;

use App\Models\Todo;
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
            'search' => 'sometimes|array',
            'search.*.column' => 'sometimes|string|in:'.implode(',', Todo::SEARCH_COLUMNS),
            'search.*.term' => 'sometimes|string|max:255',

            'order' => 'sometimes|array',
            'order.*.by' => 'sometimes|string|in:'.implode(',', Todo::INDEX_COLUMNS),
            'order.*.direction' => 'nullable|string',

            'pagination' => 'sometimes|array',
            'pagination.status' => 'nullable|boolean',
            'pagination.page' => 'nullable|integer',
            'pagination.perPage' => 'nullable|integer',
        ];
    }
}
