<?php

namespace App\Observers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use App\Services\ActivityLogService;

class TaskObserver
{
    public function created(Task $task): void
    {
        (new ActivityLogService())->log('task.created', $task, [
            'project_id' => $task->project_id,
            'assigned_to' => $task->assigned_to,
            'priority' => $task->priority,
            'status' => $task->status,
        ]);

        $this->notifyAssignedDeveloper($task);
    }

    public function updated(Task $task): void
    {
        (new ActivityLogService())->log('task.updated', $task, [
            'changes' => $task->getChanges(),
        ]);

        if ($task->wasChanged('assigned_to')) {
            $this->notifyAssignedDeveloper($task);
        }
    }

    public function deleted(Task $task): void
    {
        (new ActivityLogService())->log('task.deleted', $task, [
            'project_id' => $task->project_id,
        ]);
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
