<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\ActivityLogService;

class ProjectObserver
{
    public function created(Project $project): void
    {
        (new ActivityLogService())->log('project.created', $project, [
            'name' => $project->name,
            'status' => $project->status,
            'deadline' => $project->deadline,
        ]);
    }

    public function updated(Project $project): void
    {
        (new ActivityLogService())->log('project.updated', $project, [
            'changes' => $project->getChanges(),
        ]);
    }

    public function deleted(Project $project): void
    {
        (new ActivityLogService())->log('project.deleted', $project, [
            'name' => $project->name,
        ]);
    }
}
