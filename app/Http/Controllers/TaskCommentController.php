<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate(['comment' => 'required|string|max:1000']);

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
        ]);

        ActivityLog::create([
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'action'      => 'commented',
            'description' => auth()->user()->name . ' added a comment.',
        ]);

        return back()->with('success', 'Comment added.');
    }

    public function destroy(Task $task, TaskComment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
