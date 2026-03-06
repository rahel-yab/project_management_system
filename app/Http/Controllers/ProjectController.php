<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest; 
use App\Services\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    protected $projectService;

    // Inject the service through the constructor
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(): JsonResponse
    {
        $projects = $this->projectService->getAllProjects();
        return response()->json($projects);
    }

    public function store(StoreProjectRequest $request): JsonResponse
    {
        // The Request class handles validation automatically
        $project = $this->projectService->createProject($request->validated());

        return response()->json([
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }
}

