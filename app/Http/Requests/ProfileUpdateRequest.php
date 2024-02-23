<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:5',
            'first_name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'min:8',
                Rule::unique('users')->ignore(auth()->id()),
                'regex:/^(?!.*[_.-]{2})[a-zA-Z0-9_.-]{4,}@(?!-)(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/',
            ],
            'icon_profile' => 'image',
            'date_birth' => 'required|date'
        ];
    }
}
