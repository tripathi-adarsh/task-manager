@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', 'Tasks')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1e293b">All Tasks</h5>
        <p class="mb-0 mt-1" style="color:#94a3b8;font-size:13px">{{ $tasks->total() }} task{{ $tasks->total() != 1 ? 's' : '' }} found</p>
    </div>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> New Task
    </a>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text" style="background:#f8fafc;border-color:#e2e8f0"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search tasks..." value="{{ request('search') }}" style="border-left:none">
                </div>
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    @foreach(['pending','in_progress','completed','cancelled'] as $s)
                        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="priority" class="form-select form-select-sm">
                    <option value="">All Priority</option>
                    @foreach(['high','medium','low'] as $p)
                        <option value="{{ $p }}" {{ request('priority')==$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select name="category" class="form-select form-select-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-grow-1">Filter</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#64748b">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Tasks Table --}}
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="width:40px">#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Due Date</th>
                        <th>Assigned</th>
                        <th style="width:90px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr class="{{ $task->isOverdue() ? 'overdue-row' : '' }}">
                        <td style="color:#cbd5e1;font-size:12px">{{ $task->id }}</td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="text-decoration-none" style="color:#1e293b;font-weight:500">
                                {{ $task->title }}
                            </a>
                            @if($task->description)
                                <div style="color:#94a3b8;font-size:12px;margin-top:2px">{{ Str::limit($task->description, 45) }}</div>
                            @endif
                        </td>
                        <td>
                            @if($task->category)
                                <span class="badge" style="background:{{ $task->category->color }}20;color:{{ $task->category->color }}">{{ $task->category->name }}</span>
                            @else
                                <span style="color:#cbd5e1">—</span>
                            @endif
                        </td>
                        <td><span class="badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
                        <td>
                            <select class="form-select form-select-sm status-select" data-id="{{ $task->id }}" style="width:130px;font-size:12px;border-radius:6px">
                                @foreach(['pending','in_progress','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $task->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="min-width:90px">
                            @php $prog = $task->progress ?? 0; @endphp
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height:5px">
                                    <div class="progress-bar" style="width:{{ $prog }}%;background:#6366f1"></div>
                                </div>
                                <span style="font-size:11px;color:#94a3b8;white-space:nowrap">{{ $prog }}%</span>
                            </div>
                        </td>
                        <td>
                            @if($task->due_date)
                                <span style="font-size:13px;{{ $task->isOverdue() ? 'color:#ef4444;font-weight:600' : 'color:#94a3b8' }}">
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($task->isOverdue()) <i class="bi bi-exclamation-circle ms-1"></i> @endif
                                </span>
                            @else
                                <span style="color:#cbd5e1">—</span>
                            @endif
                        </td>
                        <td style="color:#94a3b8;font-size:13px">{{ $task->assignee ? $task->assignee->name : '—' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-icon" style="background:#eff6ff;color:#6366f1;border:none" title="Edit">
                                    <i class="bi bi-pencil" style="font-size:13px"></i>
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon" style="background:#fef2f2;color:#ef4444;border:none" title="Delete">
                                        <i class="bi bi-trash" style="font-size:13px"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-inbox" style="font-size:40px;display:block;margin-bottom:10px;opacity:.4"></i>
                            No tasks found.
                            <a href="{{ route('tasks.create') }}" style="color:#6366f1">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tasks->hasPages())
    <div class="card-footer bg-white" style="border-top:1px solid #f1f5f9">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function() {
        const row = this.closest('tr');
        fetch(`/tasks/${this.dataset.id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
            body: JSON.stringify({ status: this.value })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                row.style.transition = 'background .4s';
                row.style.background = '#f0fdf4';
                setTimeout(() => row.style.background = '', 1200);
            }
        });
    });
});
</script>
@endsection
