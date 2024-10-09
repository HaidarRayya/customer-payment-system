<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class CartRequestService
{
    /**
     *  get array of  CartRequestService attributes 
     *
     * @return array   of attributes
     */
    public function attributes()
    {
        return  [
            'product_id' => 'رقم الكتاب',
            'count' => 'الكمية',
        ];
    }
    /**
     *  
     * @param $validator
     *
     * throw a exception
     */
    public function failedValidation($validator)
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
     *  get array of  CartRequestService messages 
     * @return array   of messages
     */
    public function messages()
    {
        return  [
            'required' => 'حقل :attribute هو حقل اجباري ',
            'exists' => 'حقل :attribute خاطئ يرجى التأكد من رقم الكتاب',
            'integer' => 'حقل :attribute يجب ان يكون رقم',
            'gt' => 'حقل :attribute يجب ان يكون اكبر من الصفر',
        ];
    }
}
