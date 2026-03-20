@extends('layouts.app')
@section('title', 'Edit Task')
@section('page-title', 'Edit Task')

@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('tasks.index') }}" class="btn btn-icon" style="background:#f1f5f9;color:#64748b;border:none">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div class="flex-grow-1">
        <h5 class="mb-0 fw-bold" style="color:#1e293b">{{ Str::limit($task->title, 50) }}</h5>
        <p class="mb-0 mt-1" style="color:#94a3b8;font-size:13px">
            Created {{ $task->created_at->diffForHumans() }}
            @if($task->creator) by {{ $task->creator->name }} @endif
        </p>
    </div>
    <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-sm" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca">
            <i class="bi bi-trash me-1"></i>Delete
        </button>
    </form>
</div>

<div class="row g-4">
    {{-- Left Column --}}
    <div class="col-lg-8">

        {{-- Edit Form --}}
        <div class="card mb-4">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-pencil-square" style="color:#6366f1"></i>
                <span style="font-weight:600">Task Details</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('tasks.update', $task) }}" method="POST">
                    @csrf @method('PUT')
                    @include('admin.tasks._form')
                    <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #f1f5f9">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>Save Changes
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn" style="background:#f1f5f9;color:#64748b">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="card mb-4">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-paperclip" style="color:#64748b"></i>
                <span style="font-weight:600">Attachments</span>
                <span class="badge ms-auto" style="background:#f1f5f9;color:#64748b">{{ $task->attachments->count() }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.attachments.store', $task) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex gap-2 mb-3">
                        <input type="file" name="attachment" class="form-control form-control-sm" required>
                        <button type="submit" class="btn btn-sm btn-primary text-nowrap">
                            <i class="bi bi-upload me-1"></i>Upload
                        </button>
                    </div>
                </form>
                @forelse($task->attachments as $att)
                <div class="d-flex align-items-center justify-content-between p-3 mb-2 rounded" style="background:#f8fafc;border:1px solid #f1f5f9">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:36px;height:36px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
                            <i class="bi bi-file-earmark" style="color:#6366f1"></i>
                        </div>
                        <div>
                            <a href="{{ $att->url }}" target="_blank" class="text-decoration-none" style="color:#1e293b;font-weight:500;font-size:13.5px">{{ $att->original_name }}</a>
                            <div style="color:#94a3b8;font-size:11px">{{ $att->formatted_size }} &bull; {{ $att->user->name }} &bull; {{ $att->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <form action="{{ route('tasks.attachments.destroy', [$task, $att]) }}" method="POST" onsubmit="return confirm('Delete?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-icon" style="background:#fef2f2;color:#ef4444;border:none"><i class="bi bi-trash" style="font-size:13px"></i></button>
                    </form>
                </div>
                @empty
                <p style="color:#94a3b8;font-size:13px;margin:0">No attachments yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Comments --}}
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-chat-dots" style="color:#06b6d4"></i>
                <span style="font-weight:600">Comments</span>
                <span class="badge ms-auto" style="background:#f1f5f9;color:#64748b">{{ $task->comments->count() }}</span>
            </div>
            <div class="card-body">
                <form action="{{ route('tasks.comments.store', $task) }}" method="POST" class="mb-4">
                    @csrf
                    <div class="d-flex gap-3">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-grow-1">
                            <textarea name="comment" class="form-control" rows="2" placeholder="Write a comment..." required style="resize:none"></textarea>
                            <button type="submit" class="btn btn-sm btn-primary mt-2">
                                <i class="bi bi-send me-1"></i>Post Comment
                            </button>
                        </div>
                    </div>
                </form>

                @forelse($task->comments as $comment)
                <div class="d-flex gap-3 mb-4">
                    <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0">
                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="p-3 rounded" style="background:#f8fafc;border:1px solid #f1f5f9">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="font-weight:600;font-size:13.5px;color:#1e293b">{{ $comment->user->name }}</span>
                                <span style="color:#94a3b8;font-size:11px">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mb-0" style="font-size:13.5px;color:#374151">{{ $comment->comment }}</p>
                        </div>
                        @if($comment->user_id === auth()->id() || auth()->user()->isAdmin())
                        <form action="{{ route('tasks.comments.destroy', [$task, $comment]) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-link p-0 mt-1" style="font-size:12px;color:#ef4444;text-decoration:none">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <p style="color:#94a3b8;font-size:13px;text-align:center;padding:20px 0">No comments yet. Be the first!</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Right Column: Activity Log --}}
    <div class="col-lg-4">
        <div class="card" style="position:sticky;top:80px">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-activity" style="color:#10b981"></i>
                <span style="font-weight:600">Activity Log</span>
            </div>
            <div style="max-height:600px;overflow-y:auto">
                @forelse($task->activityLogs as $log)
                @php
                    $cfg = [
                        'created'          => ['bi-plus-circle-fill','#10b981','#f0fdf4'],
                        'updated'          => ['bi-pencil-fill','#f59e0b','#fffbeb'],
                        'status_changed'   => ['bi-arrow-repeat','#6366f1','#eff6ff'],
                        'commented'        => ['bi-chat-fill','#06b6d4','#ecfeff'],
                        'attachment_added' => ['bi-paperclip','#64748b','#f8fafc'],
                    ];
                    [$icon, $color, $bg] = $cfg[$log->action] ?? ['bi-dot','#94a3b8','#f8fafc'];
                @endphp
                <div class="d-flex gap-3 px-4 py-3" style="border-bottom:1px solid #f8fafc">
                    <div style="width:30px;height:30px;border-radius:8px;background:{{ $bg }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
                        <i class="bi {{ $icon }}" style="color:{{ $color }};font-size:13px"></i>
                    </div>
                    <div>
                        <p class="mb-0" style="font-size:13px;color:#374151">{{ $log->description }}</p>
                        <span style="font-size:11px;color:#94a3b8">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-5" style="color:#94a3b8;font-size:13px">
                    <i class="bi bi-clock-history d-block fs-3 mb-2 opacity-50"></i>No activity yet.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
