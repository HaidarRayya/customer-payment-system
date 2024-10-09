<?php

namespace App\Http\Requests\Transfer;

use App\Enums\UserRole;
use App\Rules\CheckValidAmount;
use App\Rules\CheckValidEmail;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class StoreTransferRequest extends FormRequest
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
        if (Auth::user()->role == UserRole::POINT_RELITIER->value) {
            $data['amount'] =  ['required', 'numeric', 'gt:0', new CheckValidAmount];
        } else if (Auth::user()->role == UserRole::ADMIN->value) {
            $data['amount'] = ['required', 'numeric', 'gt:0'];
        }
        return  [
            'email' =>  ['required', 'email', 'exists:users,email', new CheckValidEmail],
            ...$data
        ];
    }

    public function attributes(): array
    {
        return  [
            'email' => 'الايميل',
            'amount' => 'الكمية'
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
            'required' => 'حقل :attribute هو حقل اجباري ',
            'exists' => 'هذا الحساب غير موجود  يرجى التأكد',
            'gt' => 'حقل :attribute يجب ان يكون عدد اكبر من الصفر',
            'numeric' => 'حقل :attribute   يجب ان يكون عدد ',
            'email.email' => 'حقل :attribute خاطى يرجى التأكد من كتابته ',
        ];
    }
}
