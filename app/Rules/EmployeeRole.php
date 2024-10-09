<?php

namespace App\Rules;

use App\Enums\UserRole as EnumsUserRole;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeRole implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $point_reliter_role = EnumsUserRole::POINT_RELITIER->value;
        if ($point_reliter_role != $value) {
            $fail($point_reliter_role . " حقل :attribute  يجب ان يكون   .");
        }
    }
}