<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class TaskAttachmentController extends Controller
{
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'attachment' => 'required|file|max:10240', // 10MB max
        ]);

        $file     = $request->file('attachment');
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('attachments', $filename, 'public');

        $task->attachments()->create([
            'user_id'       => auth()->id(),
            'filename'      => $filename,
            'original_name' => $file->getClientOriginalName(),
            'mime_type'     => $file->getMimeType(),
            'size'          => $file->getSize(),
        ]);

        ActivityLog::create([
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'action'      => 'attachment_added',
            'description' => auth()->user()->name . ' uploaded "' . $file->getClientOriginalName() . '".',
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function destroy(Task $task, TaskAttachment $attachment)
    {
        if ($attachment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }
        \Storage::disk('public')->delete('attachments/' . $attachment->filename);
        $attachment->delete();
        return back()->with('success', 'Attachment deleted.');
    }
}
