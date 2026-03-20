<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Task Manager') - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root { --sidebar-width: 250px; }
        body { background: #f0f2f5; font-family: 'Segoe UI', sans-serif; }
        .sidebar {
            width: var(--sidebar-width); min-height: 100vh; background: #1e2a3a;
            position: fixed; top: 0; left: 0; z-index: 100; transition: .3s;
        }
        .sidebar .brand { padding: 20px; border-bottom: 1px solid rgba(255,255,255,.1); }
        .sidebar .brand h5 { color: #fff; margin: 0; font-weight: 700; }
        .sidebar .brand small { color: #8899aa; font-size: 11px; }
        .sidebar .nav-link {
            color: #8899aa; padding: 10px 20px; display: flex; align-items: center; gap: 10px;
            border-radius: 0; transition: .2s; font-size: 14px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: #fff; background: rgba(255,255,255,.08);
            border-left: 3px solid #0d6efd;
        }
        .sidebar .nav-section { padding: 12px 20px 4px; color: #556677; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; }
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }
        .topbar {
            background: #fff; padding: 12px 24px; border-bottom: 1px solid #e9ecef;
            display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 99;
        }
        .page-content { padding: 24px; }
        .stat-card { border: none; border-radius: 12px; transition: .2s; }
        .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.1); }
        .badge-priority-high   { background: #dc3545; }
        .badge-priority-medium { background: #fd7e14; }
        .badge-priority-low    { background: #20c997; }
        .table th { font-size: 12px; text-transform: uppercase; letter-spacing: .5px; color: #6c757d; border-top: none; }
        @media(max-width:768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="brand">
        <h5><i class="bi bi-check2-square me-2 text-primary"></i>TaskManager</h5>
        <small>Admin Panel</small>
    </div>
    <nav class="mt-2">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
            <i class="bi bi-list-task"></i> Tasks
        </a>
        @if(auth()->user()->isAdmin())
        <div class="nav-section">Management</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i> Categories
        </a>
        @endif
        <div class="nav-section">Account</div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>
    </nav>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="bi bi-list"></i>
            </button>
            <h6 class="mb-0 text-muted">@yield('page-title', 'Dashboard')</h6>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'primary' }}">
                {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
            </span>
            <span class="text-muted small">{{ auth()->user()->name }}</span>
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
