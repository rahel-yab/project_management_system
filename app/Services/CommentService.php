<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentService
{
    public function addComment(array $data): Comment
    {
        // Business Rule: The author is always the currently logged-in user
        $data['user_id'] = Auth::id();
        
        return Comment::create($data);
    }

    public function getCommentsForTask(int $taskId)
    {
        return Comment::where('task_id', $taskId)
            ->with('user:id,name') // Eager load the author's name
            ->latest()
            ->paginate(10);
    }
}