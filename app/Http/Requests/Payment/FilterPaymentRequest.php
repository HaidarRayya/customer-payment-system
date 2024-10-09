<?php

namespace App\Http\Requests\Payment;

use App\Rules\CheckPaymentSearch;
use App\Rules\CheckPaymentStatus;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class  FilterPaymentRequest extends FormRequest
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
            'status' => ['sometimes', 'string', new CheckPaymentStatus],
            'user_name' => ['sometimes', 'string'],
            'payment' => ['sometimes', 'string', new CheckPaymentSearch],
            'user_id' => ['sometimes', 'integer', 'gt:0', 'exists:users,id'],
        ];
    }

    public function attributes(): array
    {
        return  [
            'status' => 'حالة الطلبية',
            'user_name' => 'اسم المستخدم',
            'payment' => 'الدفعة',
            'user_id' => 'رقم الزبون',

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
            'string' => 'حقل :attribute يجب ان يكون نص',
            'exists' => 'حقل :attribute خاطئ يرجى التأكد من رقم الفئة',
            'integer' => 'حقل :attribute يجب ان يكون عدد صحيح',
            'gt' => 'حقل :attribute يجب ان يكون عدد اكبر من الصفر',
        ];
    }
}
