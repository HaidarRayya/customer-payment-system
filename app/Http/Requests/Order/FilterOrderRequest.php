<?php

namespace App\Http\Requests\Order;

use App\Rules\CheckOrderStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class   FilterOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'status' => ['sometimes', 'string', new CheckOrderStatus],
        ];
    }

    public function attributes(): array
    {
        return  [
            'status' => 'حالة الطلبية'
        ];
    }
    /**
     *  
     * @param $validator
     *
     * throw a exception
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'status' => 'error',
                'message' => "فشل التحقق يرجى التأكد من صحة القيم مدخلة",
                'errors' => $validator->errors()
            ],
            422
        ));
    }
    /**
     *  get array of  BookRequestService messages 
     * @return array   of messages
     */
    public function messages()
    {
        return  [
            'required' => 'حقل :attribute هو حقل اجباري ',
            'string' => 'حقل :attribute يجب ان يكون نص',
        ];
    }
}