<?php

namespace App\Enums;

enum VacationTypeId: int
{
    case HOSPITAL = 2;

    public static function isHospital($value): bool
    {
        return (int) $value === self::HOSPITAL->value;
    }
}
