<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;

class TaskObserver
{
    public function created(Task $task): void
    {
        $this->notifyAssignedDeveloper($task);
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged('assigned_to')) {
            $this->notifyAssignedDeveloper($task);
        }
    }

    private function notifyAssignedDeveloper(Task $task): void
    {
        $task->loadMissing('project');

        $developer = $task->developer;

        if (! $developer) {
            return;
        }

        $developer->notify(new TaskAssignedNotification($task));
    }
}
