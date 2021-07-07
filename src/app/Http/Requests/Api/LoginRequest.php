<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'bail|required|string|max:255|email',
            'password' => 'bail|required|string|max:255',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => __('text.auth.email'),
            'password' => __('text.auth.password'),
        ];
    }
}
