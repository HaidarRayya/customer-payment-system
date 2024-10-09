<?php

namespace App\Rules;

use App\Enums\OrderStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckOrderStatus implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $orderStatus = array_column(OrderStatus::cases(), 'value');
        $status = implode(", ", $orderStatus);

        if (!(in_array($value, $orderStatus))) {
            $fail($status . " حقل :attribute  يجب ان يكون احد القيم .");
        }
    }
}