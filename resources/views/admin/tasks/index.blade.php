@extends('layouts.app')
@section('title', 'Tasks')
@section('page-title', 'Task Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">All Tasks</h5>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Task
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search tasks..." value="{{ request('search') }}">
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
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Tasks Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Assigned To</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $task)
                    <tr>
                        <td class="text-muted small">{{ $task->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $task->title }}</div>
                            @if($task->description)
                                <small class="text-muted">{{ Str::limit($task->description, 40) }}</small>
                            @endif
                        </td>
                        <td>
                            @if($task->category)
                                <span class="badge" style="background:{{ $task->category->color }}">{{ $task->category->name }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            @php $pc = ['high'=>'danger','medium'=>'warning','low'=>'success']; @endphp
                            <span class="badge bg-{{ $pc[$task->priority] ?? 'secondary' }} {{ $task->priority==='medium'?'text-dark':'' }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td>
                            @php $sc = ['pending'=>'warning','in_progress'=>'info','completed'=>'success','cancelled'=>'secondary']; @endphp
                            <select class="form-select form-select-sm status-select" data-id="{{ $task->id }}" style="width:130px">
                                @foreach(['pending','in_progress','completed','cancelled'] as $s)
                                    <option value="{{ $s }}" {{ $task->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            @if($task->due_date)
                                <span class="{{ $task->isOverdue() ? 'text-danger fw-semibold' : 'text-muted' }} small">
                                    {{ $task->due_date->format('M d, Y') }}
                                    @if($task->isOverdue()) <i class="bi bi-exclamation-circle"></i> @endif
                                </span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $task->assignee ? $task->assignee->name : '—' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Delete this task?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-2"></i>No tasks found.
                            <a href="{{ route('tasks.create') }}">Create one</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($tasks->hasPages())
    <div class="card-footer bg-white">
        {{ $tasks->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.status-select').forEach(sel => {
    sel.addEventListener('change', function() {
        fetch(`/tasks/${this.dataset.id}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ status: this.value })
        }).then(r => r.json()).then(d => {
            if (d.success) {
                const row = this.closest('tr');
                row.style.transition = 'background .3s';
                row.style.background = '#d1e7dd';
                setTimeout(() => row.style.background = '', 1000);
            }
        });
    });
});
</script>
@endsection
