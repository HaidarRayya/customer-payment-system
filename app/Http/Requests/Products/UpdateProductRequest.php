<?php

namespace App\Http\Requests\Products;

use App\Services\ProductRequestService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    protected $productRequestService;
    public function __construct(ProductRequestService $productRequestService)
    {
        $this->productRequestService = $productRequestService;
    }
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
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
            'name' => 'sometimes|min:3|max:255|string',
            'image' => 'sometimes|mimes:png,jpg',
            'count' => 'sometimes|integer|gt:0',
            'price' => 'sometimes|numeric|gt:0',
        ];
    }

    public function attributes(): array
    {
        return  $this->productRequestService->attributes();
    }
    public function failedValidation(Validator $validator)
    {
        $this->productRequestService->failedValidation($validator);
    }
    public function messages(): array
    {
        return $this->productRequestService->messages();
    }
}