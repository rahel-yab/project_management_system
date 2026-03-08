<?php

namespace App\Providers;

use App\Models\Project;
use App\Observers\TaskObserver;
use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
{
    Gate::policy(Project::class, ProjectPolicy::class);
    Gate::policy(Task::class, TaskPolicy::class);
    Task::observe(TaskObserver::class);
}
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
}
