@extends('layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

{{-- Stat Cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-blue">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon" style="background:rgba(255,255,255,.2)">
                    <i class="bi bi-kanban text-white"></i>
                </div>
            </div>
            <div class="stat-num">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Tasks</div>
            <i class="bi bi-kanban stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-amber">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon" style="background:rgba(255,255,255,.2)">
                    <i class="bi bi-hourglass-split text-white"></i>
                </div>
            </div>
            <div class="stat-num">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pending</div>
            <i class="bi bi-hourglass-split stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-cyan">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon" style="background:rgba(255,255,255,.2)">
                    <i class="bi bi-arrow-repeat text-white"></i>
                </div>
            </div>
            <div class="stat-num">{{ $stats['in_progress'] }}</div>
            <div class="stat-label">In Progress</div>
            <i class="bi bi-arrow-repeat stat-bg-icon"></i>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="stat-card stat-green">
            <div class="d-flex justify-content-between align-items-start">
                <div class="stat-icon" style="background:rgba(255,255,255,.2)">
                    <i class="bi bi-check-circle text-white"></i>
                </div>
            </div>
            <div class="stat-num">{{ $stats['completed'] }}</div>
            <div class="stat-label">Completed</div>
            <i class="bi bi-check-circle stat-bg-icon"></i>
        </div>
    </div>
</div>

{{-- Overdue Alert --}}
@if($stats['overdue'] > 0)
<div class="alert alert-danger d-flex align-items-center gap-3 mb-4" style="border-left: 4px solid #dc2626;">
    <i class="bi bi-exclamation-triangle-fill fs-4 flex-shrink-0"></i>
    <div class="flex-grow-1">
        <strong>{{ $stats['overdue'] }} overdue task{{ $stats['overdue'] > 1 ? 's' : '' }}</strong> need your attention.
    </div>
    <a href="{{ route('tasks.index') }}?status=pending" class="btn btn-sm btn-danger text-nowrap">View Now</a>
</div>
@endif

<div class="row g-4">
    {{-- Recent Tasks --}}
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-600 d-flex align-items-center gap-2" style="font-weight:600">
                    <span style="width:8px;height:8px;border-radius:50%;background:#6366f1;display:inline-block"></span>
                    Recent Tasks
                </span>
                <a href="{{ route('tasks.index') }}" class="btn btn-sm" style="background:#f1f5f9;color:#6366f1;font-size:12px">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
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
                                <a href="{{ route('tasks.edit', $task) }}" class="text-decoration-none fw-500" style="color:#1e293b;font-weight:500">
                                    {{ Str::limit($task->title, 32) }}
                                </a>
                                @if($task->category)
                                    <br>
                                    <span class="badge mt-1" style="background:{{ $task->category->color }}20;color:{{ $task->category->color }};font-size:10px">
                                        {{ $task->category->name }}
                                    </span>
                                @endif
                            </td>
                            <td><span class="badge badge-status-{{ $task->status }}">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span></td>
                            <td><span class="badge badge-priority-{{ $task->priority }}">{{ ucfirst($task->priority) }}</span></td>
                            <td style="color:#94a3b8;font-size:13px">{{ $task->assignee ? $task->assignee->name : '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5" style="color:#94a3b8">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>No tasks yet
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-5 d-flex flex-column gap-4">

        {{-- Upcoming Deadlines --}}
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <span style="width:8px;height:8px;border-radius:50%;background:#ef4444;display:inline-block"></span>
                <span style="font-weight:600">Upcoming Deadlines</span>
            </div>
            <div class="card-body p-0">
                @forelse($upcomingTasks as $task)
                <a href="{{ route('tasks.edit', $task) }}" class="d-flex align-items-center justify-content-between px-4 py-3 text-decoration-none border-bottom" style="transition:.15s" onmouseover="this.style.background='#fafbff'" onmouseout="this.style.background=''">
                    <div>
                        <div style="font-weight:500;color:#1e293b;font-size:13.5px">{{ Str::limit($task->title, 30) }}</div>
                        <div style="color:#94a3b8;font-size:12px">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</div>
                    </div>
                    <span class="badge {{ $task->due_date->diffInDays(now()) <= 1 ? 'badge-priority-high' : 'badge-priority-medium' }}">
                        {{ $task->due_date->format('M d') }}
                    </span>
                </a>
                @empty
                <div class="text-center py-4" style="color:#94a3b8;font-size:13px">
                    <i class="bi bi-calendar-check d-block fs-3 mb-1"></i>No upcoming deadlines
                </div>
                @endforelse
            </div>
        </div>

        {{-- Categories --}}
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="d-flex align-items-center gap-2">
                    <span style="width:8px;height:8px;border-radius:50%;background:#10b981;display:inline-block"></span>
                    <span style="font-weight:600">Categories</span>
                </span>
                @if(auth()->user()->isAdmin())
                <a href="{{ route('categories.index') }}" style="font-size:12px;color:#6366f1;text-decoration:none">Manage</a>
                @endif
            </div>
            <div class="card-body">
                @foreach($categories as $cat)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color }};display:inline-block;flex-shrink:0"></span>
                        <span style="font-size:13.5px;color:#374151">{{ $cat->name }}</span>
                    </div>
                    <span class="badge" style="background:#f1f5f9;color:#64748b">{{ $cat->tasks_count }}</span>
                </div>
                @endforeach
            </div>
        </div>

        @if($totalUsers !== null)
        <div class="card">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <div style="font-size:30px;font-weight:700;color:#6366f1">{{ $totalUsers }}</div>
                    <div style="color:#94a3b8;font-size:13px">Total Users</div>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-sm" style="background:#eff6ff;color:#6366f1;border:1px solid #e0e7ff">
                    <i class="bi bi-people me-1"></i>Manage
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
