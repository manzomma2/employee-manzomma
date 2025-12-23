<?php

namespace App\Enums;

enum GradeLevel: string
{
    case A = 'أ';
    case B = 'ب';
    case C = 'ج';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
