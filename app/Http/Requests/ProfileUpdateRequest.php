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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'username' => [
                'required',
                'string',
                'max:100',
                Rule::unique(User::class, 'username')
                    ->ignore($this->user()?->getKey(), $this->user()?->getKeyName()),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')
                    ->ignore($this->user()?->getKey(), $this->user()?->getKeyName()),
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'domicile' => ['nullable', 'string', 'max:100'],
            'born_date' => ['nullable', 'date'],
            'gender' => ['nullable', Rule::in(['male', 'female'])],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
