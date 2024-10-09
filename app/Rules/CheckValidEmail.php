<?php

namespace App\Rules;

use App\Enums\UserRole;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Auth;

class CheckValidEmail implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $role = Auth::user()->role;
        $user = User::byEmail($value)->first();
        if ($user)
            if ($role == UserRole::ADMIN->value) {
                if ($user->role != UserRole::POINT_RELITIER->value) {
                    $fail("الايميل الذي ادخلته ليس لموزع نقاط");
                }
            } else if ($role == UserRole::POINT_RELITIER->value) {
                if ($user->role != UserRole::CUSTOMER->value) {
                    $fail("الايميل الذي ادخلته ليس لزبون ");
                }
            }
    }
}
