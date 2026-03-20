@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-2 fw-bold">{{ $stats['total'] }}</div>
                        <div class="small opacity-75">Total Tasks</div>
                    </div>
                    <i class="bi bi-list-task fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-2 fw-bold">{{ $stats['pending'] }}</div>
                        <div class="small opacity-75">Pending</div>
                    </div>
                    <i class="bi bi-hourglass-split fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-2 fw-bold">{{ $stats['in_progress'] }}</div>
                        <div class="small opacity-75">In Progress</div>
                    </div>
                    <i class="bi bi-arrow-repeat fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fs-2 fw-bold">{{ $stats['completed'] }}</div>
                        <div class="small opacity-75">Completed</div>
                    </div>
                    <i class="bi bi-check-circle fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

@if($stats['overdue'] > 0)
<div class="alert alert-danger d-flex align-items-center mb-4">
    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
    <strong>{{ $stats['overdue'] }} overdue task(s)</strong>&nbsp;need your attention.
    <a href="{{ route('tasks.index') }}?status=pending" class="ms-auto btn btn-sm btn-danger">View Tasks</a>
</div>
@endif

<div class="row g-4">
    <!-- Recent Tasks -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Tasks</h6>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Assigned</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTasks as $task)
                            <tr>
                                <td>
                                    <a href="{{ route('tasks.edit', $task) }}" class="text-decoration-none text-dark fw-semibold">
                                        {{ Str::limit($task->title, 30) }}
                                    </a>
                                    @if($task->category)
                                        <br><span class="badge" style="background:{{ $task->category->color }}; font-size:10px">{{ $task->category->name }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $sc = ['pending'=>'warning','in_progress'=>'info','completed'=>'success','cancelled'=>'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $sc[$task->status] ?? 'secondary' }} text-{{ $task->status==='pending'?'dark':'' }}">
                                        {{ ucfirst(str_replace('_',' ',$task->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span>
                                </td>
                                <td class="small text-muted">{{ $task->assignee ? $task->assignee->name : '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No tasks yet</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-5">
        <!-- Upcoming Deadlines -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-calendar-event me-2 text-danger"></i>Upcoming Deadlines</h6>
            </div>
            <div class="list-group list-group-flush">
                @forelse($upcomingTasks as $task)
                <a href="{{ route('tasks.edit', $task) }}" class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold small">{{ Str::limit($task->title, 28) }}</span>
                        <span class="badge bg-{{ $task->due_date->diffInDays(now()) <= 1 ? 'danger' : 'warning' }} text-dark small">
                            {{ $task->due_date->format('M d') }}
                        </span>
                    </div>
                    <small class="text-muted">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</small>
                </a>
                @empty
                <div class="list-group-item text-muted text-center small py-3">No upcoming deadlines</div>
                @endforelse
            </div>
        </div>

        <!-- Categories -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-tags me-2 text-success"></i>Categories</h6>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.index') }}" class="btn btn-sm btn-outline-success">Manage</a>
                @endif
            </div>
            <div class="card-body">
                @foreach($categories as $cat)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color }};display:inline-block"></span>
                        <span class="small">{{ $cat->name }}</span>
                    </span>
                    <span class="badge bg-light text-dark">{{ $cat->tasks_count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @if($totalUsers !== null)
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-3 fw-bold text-primary">{{ $totalUsers }}</div>
                    <div class="small text-muted">Total Users</div>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-people me-1"></i>Manage Users
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
