<?php

namespace App\Http\Requests\Category;

use App\Services\CategoryRequestService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreCategoryRequest extends FormRequest
{

    protected $categoryRequestService;
    public function __construct(CategoryRequestService $categoryRequestService)
    {
        $this->categoryRequestService = $categoryRequestService;
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
            'name' => 'required|min:3|max:255|unique:categories,name|string',
            'description' => 'sometimes|nullable|min:3|max:255|string',
        ];
    }

    public function attributes(): array
    {
        return  $this->categoryRequestService->attributes();
    }
    public function failedValidation(Validator $validator)
    {
        $this->categoryRequestService->failedValidation($validator);
    }
    public function messages(): array
    {
        $messages = $this->categoryRequestService->messages();
        $messages['required'] = 'حقل :attribute هو حقل اجباري ';
        return $messages;
    }
}
