<?php

namespace App\Http\Requests\Transfer;

use App\Rules\UserRole;
use App\Enums\UserRole as EnumsUserRole;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class FilterTransferRequest extends FormRequest
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
        $data = [];
        if (Auth::user()->role == EnumsUserRole::ADMIN->value) {
            $data['role'] =  ['sometimes', new UserRole];
        }
        return  [
            'user_name' =>  ['sometimes', 'string'],
            'amount' =>  ['sometimes',  'numeric', 'gt:0'],
            'date' =>  ['sometimes',  'date_format:Y-d-m'],
            'sort' =>  ['sometimes', 'in:ASC,DESC'],
            ...$data
        ];
    }

    public function attributes(): array
    {
        return  [
            'user_name' => 'اسم المستخدم',
            'role' => 'الدور',
            'amount' => 'الكمية',
            'date' => 'التاريخ',
            'sort' => 'الترتيب'
        ];
    }
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
    public function messages(): array
    {
        return  [
            'gt' => 'حقل :attribute يجب ان يكون عدد اكبر من الصفر',
            'numeric' => 'حقل :attribute   يجب ان يكون عدد ',
            'date_format' => 'حقل :attribute  يجب ان يكون من الشكل سنة-شهر-يوم ',
            'string' => 'حقل :attribute يجب ان يكون نص',
            'in' =>  'ASC , DESC' . 'حقل :attribute يجب ان يكون احد القيمتين ',
        ];
    }
}
