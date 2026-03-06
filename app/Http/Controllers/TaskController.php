<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\TaskService;
use App\Http\Resources\TaskResource; // 1. Import the Resource
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(protected TaskService $taskService) {}

    public function store(Request $request) 
    {
        // ... validation ...
        $task = $this->taskService->createTask($request->all());

        // 2. Wrap the result in the Resource
        return new TaskResource($task); 
    }

    public function updateStatus(Request $request, Task $task)
    {
        Gate::authorize('updateStatus', $task);

        $updatedTask = $this->taskService->updateStatus($task, $request->status);
        
        // 3. Instead of a manual array, return the Resource with a message
        return (new TaskResource($updatedTask))
            ->additional(['message' => 'Status updated successfully']);
    }

    public function index()
    {
        $tasks = $this->taskService->getAllTasks();
        
        // 4. For multiple items, use the ::collection() method
        return TaskResource::collection($tasks);
    }
}
