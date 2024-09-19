<?php

namespace App\Http\Requests\Todo;
use App\Http\Requests\BaseRequest;
use App\Rules\Base64ImageSize;


class UpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $todo = $this->route('resource');
        return $todo && $todo->user_id === auth()->user()->id ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => [
                'nullable',
                'string',
                'regex:/^data:image\/(?:png|jpg|jpeg);base64,/',
                new Base64ImageSize(1024)
            ],
        ];
    }
}
