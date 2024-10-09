<?php

namespace App\Rules;

use App\Enums\PaymentSearch;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckPaymentSearch implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $paymentSearch = array_column(PaymentSearch::cases(), 'value');
        $searchValue = implode(", ", $paymentSearch);

        if (!(in_array($value, $paymentSearch))) {
            $fail($searchValue . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}
