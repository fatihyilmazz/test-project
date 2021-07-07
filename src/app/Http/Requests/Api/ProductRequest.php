<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|string|max:255',
            'identifier' => 'bail|required|string|max:255',
            'description' => 'bail|nullable|max:255',
            'categories' => 'bail|required|string|max:255',
            'images' => 'bail|required|string',
            'prices' => 'bail|required|string',
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => __('text.product.name'),
            'identifier' => __('text.product.identifier'),
            'description' => __('text.product.description'),
            'categories' => __('text.product.categories'),
            'images' => __('text.product.images'),
            'prices' => __('text.product.prices'),
        ];
    }
}
