<?php

namespace App\Http\Requests\Products;

use App\Services\ProductRequestService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|min:3|max:255|string',
            'image' => 'required|mimes:png,jpg',
            'count' => 'required|integer|gt:0',
            'price' => 'required|numeric|gt:0',
            'category_id' => 'required|integer|gt:0|exists:categories,id',
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
        $messages = $this->productRequestService->messages();
        $messages['required'] = 'حقل :attribute هو حقل اجباري ';
        $messages['exists'] = 'حقل :attribute خاطئ , ترجة تأكد من رقم الفئة';
        return $messages;
    }
}