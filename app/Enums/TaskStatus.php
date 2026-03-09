<?php

namespace App\Enums;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public static function options(): array
    {
        return [
            self::TODO->value => 'To Do',
            self::IN_PROGRESS->value => 'In Progress',
            self::DONE->value => 'Done',
        ];
    }
}
