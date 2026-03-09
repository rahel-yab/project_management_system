<?php

namespace App\Enums;

enum ProjectStatus: string
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';

    public static function options(): array
    {
        return [
            self::ACTIVE->value => 'Active',
            self::COMPLETED->value => 'Completed',
        ];
    }
}
