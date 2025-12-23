<?php

namespace App\Enums;

enum Grade: string
{
    case FIRST = 'الدرجة الاولى';
    case SECOND = 'الدرجة الثانية';
    case THIRD = 'الدرجة الثالثة';
    case FOURTH = 'الدرجة الرابعة';
    case FIFTH = 'الدرجة الخامسة';
    case SIXTH = 'الدرجة السادسة';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
