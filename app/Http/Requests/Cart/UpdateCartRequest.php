<?php

namespace App\Http\Requests\Cart;

use App\Services\CartRequestService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    protected $cartRequestService;
    public function __construct(CartRequestService $cartRequestService)
    {
        $this->cartRequestService = $cartRequestService;
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
            'count' => 'required|integer|gt:0',
        ];
    }

    public function attributes(): array
    {
        return  $this->cartRequestService->attributes();
    }
    public function failedValidation(Validator $validator)
    {
        $this->cartRequestService->failedValidation($validator);
    }
    public function messages(): array
    {
        return $this->cartRequestService->messages();
    }
}