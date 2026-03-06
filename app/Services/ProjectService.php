<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    /**
     * Handle project creation logic.
     */
    public function createProject(array $data): Project
    {
        // Business logic: Always attach the logged-in user as the creator
        $data['created_by'] = Auth::id();
        
        return Project::create($data);
    }

    /**
     * Get all projects with creator details.
     */
    public function getAllProjects()
    {
        return Project::with('creator:id,name')->paginate(10);
    }
}