<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RolePermissionRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_array($value)) {
            $fail('The selected permissions payload is invalid.');

            return;
        }

        $allowedPermissions = app('permission.service')->getAllowedPermissions();

        foreach ($value as $module => $actions) {
            if (! array_key_exists($module, $allowedPermissions) || ! is_array($actions)) {
                $fail('The selected permissions payload is invalid.');

                return;
            }

            foreach ($actions as $action) {
                if (! in_array($action, $allowedPermissions[$module], true)) {
                    $fail('The selected permissions payload is invalid.');

                    return;
                }
            }
        }
    }
}