<?php

namespace App\Observers;

use App\Models\Comment;
use App\Services\ActivityLogService;

class CommentObserver
{
    public function created(Comment $comment): void
    {
        (new ActivityLogService())->log('comment.created', $comment, [
            'task_id' => $comment->task_id,
            'user_id' => $comment->user_id,
        ], $comment->user_id);
    }

    public function deleted(Comment $comment): void
    {
        (new ActivityLogService())->log('comment.deleted', $comment, [
            'task_id' => $comment->task_id,
            'user_id' => $comment->user_id,
        ], $comment->user_id);
    }
}
