<?php

namespace App\Rules;

use App\Enums\PaymentStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPaymentStatus implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $paymentStatus = array_column(PaymentStatus::cases(), 'value');
        $status = implode(", ", $paymentStatus);

        if (!(in_array($value, $paymentStatus))) {
            $fail($status . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}