<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;

class CommentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'developer'], true);
    }

    public function view(User $user, Comment $comment): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        $comment->loadMissing('task.project');

        if ($user->role === 'manager') {
            return (int) optional(optional($comment->task)->project)->created_by === (int) $user->id;
        }

        return (int) optional($comment->task)->assigned_to === (int) $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'developer'], true);
    }

    public function update(User $user, Comment $comment): bool
    {
        return (int) $comment->user_id === (int) $user->id || $user->role === 'admin';
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $this->update($user, $comment);
    }
}
