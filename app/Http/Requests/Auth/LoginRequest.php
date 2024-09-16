<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Validator;

class LoginRequest extends BaseRequest
{
    public string $email;
    public string $password;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'email'     => 'required|max:50|email',
            'password'  => 'required|min:6|max:20',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, Rule|array|string>
     */
    public function messages(): array
    {
        return [
            'email.required'    => __('validation.required'),
            'email.email'       => __('validation.email'),
            'password.required' => __('validation.required'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'email'     => 'email address',
            'password'  => 'password',
        ];
    }
}
