<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: #f0f2f5; }
        .task-card { transition: transform .2s; }
        .task-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,.1); }
        .completed-title { text-decoration: line-through; color: #6c757d; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">
            <i class="bi bi-check2-square me-2"></i>Task Manager Pro
        </span>
        @if(!$useDb)
            <span class="badge bg-warning text-dark">
                <i class="bi bi-database-x me-1"></i>Static Mode (No DB)
            </span>
        @else
            <span class="badge bg-success">
                <i class="bi bi-database-check me-1"></i>DB Connected
            </span>
        @endif
    </div>
</nav>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Add Task -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-plus-circle me-1"></i> Add New Task
        </div>
        <div class="card-body">
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="title" class="form-control" placeholder="Task title" required>
                    </div>
                    <div class="col-md-6">
                        <input type="text" name="description" class="form-control" placeholder="Description (optional)">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Add Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Task List -->
    <div class="row g-3">
        @forelse($tasks as $task)
            @php
                $task = (array) $task;
            @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card task-card h-100 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="card-title mb-0 {{ $task['status'] === 'completed' ? 'completed-title' : '' }}">
                                {{ $task['title'] }}
                            </h6>
                            <span class="badge {{ $task['status'] === 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">
                                {{ ucfirst($task['status']) }}
                            </span>
                        </div>
                        @if(!empty($task['description']))
                            <p class="card-text text-muted small">{{ $task['description'] }}</p>
                        @endif
                    </div>
                    <div class="card-footer bg-white d-flex gap-2">
                        <!-- Toggle Status -->
                        <form action="{{ route('tasks.update', $task['id']) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <input type="hidden" name="status" value="{{ $task['status'] }}">
                            <button type="submit" class="btn btn-sm w-100 {{ $task['status'] === 'completed' ? 'btn-outline-secondary' : 'btn-outline-success' }}">
                                <i class="bi {{ $task['status'] === 'completed' ? 'bi-arrow-counterclockwise' : 'bi-check-lg' }}"></i>
                                {{ $task['status'] === 'completed' ? 'Undo' : 'Complete' }}
                            </button>
                        </form>
                        <!-- Delete -->
                        <form action="{{ route('tasks.destroy', $task['id']) }}" method="POST"
                              onsubmit="return confirm('Delete this task?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">No tasks yet. Add one above.</p>
            </div>
        @endforelse
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
