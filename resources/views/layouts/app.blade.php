<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TaskFlow') — Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 260px;
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(99,102,241,.15);
            --sidebar-active: rgba(99,102,241,.2);
            --body-bg: #f1f5f9;
            --card-radius: 14px;
            --transition: .2s ease;
        }
        * { box-sizing: border-box; }
        body { background: var(--body-bg); font-family: 'Inter', sans-serif; font-size: 14px; color: #1e293b; }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w); min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed; top: 0; left: 0; z-index: 200;
            display: flex; flex-direction: column;
            transition: transform var(--transition);
            box-shadow: 4px 0 24px rgba(0,0,0,.18);
        }
        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid rgba(255,255,255,.06);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-brand .logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff; flex-shrink: 0;
        }
        .sidebar-brand .brand-text { line-height: 1.2; }
        .sidebar-brand .brand-name { color: #f8fafc; font-weight: 700; font-size: 15px; }
        .sidebar-brand .brand-sub  { color: #64748b; font-size: 11px; }

        .sidebar nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section-label {
            padding: 14px 20px 5px;
            color: #475569; font-size: 10px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1.2px;
        }
        .sidebar .nav-item { padding: 2px 10px; }
        .sidebar .nav-link {
            color: #94a3b8; padding: 9px 12px;
            display: flex; align-items: center; gap: 10px;
            border-radius: 8px; transition: var(--transition);
            font-size: 13.5px; font-weight: 500;
        }
        .sidebar .nav-link i { font-size: 16px; width: 20px; text-align: center; flex-shrink: 0; }
        .sidebar .nav-link:hover { color: #e2e8f0; background: var(--sidebar-hover); }
        .sidebar .nav-link.active {
            color: #fff; background: var(--sidebar-active);
            box-shadow: inset 3px 0 0 var(--primary);
        }
        .sidebar .nav-link.active i { color: #818cf8; }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,.06);
        }
        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: rgba(255,255,255,.04);
        }
        .sidebar-user .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .sidebar-user .user-info .user-name { color: #e2e8f0; font-size: 13px; font-weight: 600; line-height: 1.2; }
        .sidebar-user .user-info .user-role { color: #64748b; font-size: 11px; }

        /* ── Main ── */
        .main-wrap { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }

        /* ── Topbar ── */
        .topbar {
            background: #fff; padding: 0 28px;
            height: 62px; display: flex; align-items: center; justify-content: space-between;
            border-bottom: 1px solid #e2e8f0;
            position: sticky; top: 0; z-index: 100;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }
        .topbar .page-title { font-size: 16px; font-weight: 600; color: #1e293b; }
        .topbar .breadcrumb { font-size: 12px; color: #94a3b8; margin: 0; }
        .topbar-right { display: flex; align-items: center; gap: 10px; }
        .topbar-btn {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            background: #f8fafc; border: 1px solid #e2e8f0;
            color: #64748b; cursor: pointer; transition: var(--transition);
            text-decoration: none;
        }
        .topbar-btn:hover { background: #f1f5f9; color: #1e293b; }
        .topbar-user {
            display: flex; align-items: center; gap: 8px;
            padding: 5px 10px; border-radius: 8px;
            background: #f8fafc; border: 1px solid #e2e8f0;
        }
        .topbar-user .t-avatar {
            width: 28px; height: 28px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #818cf8);
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 11px; font-weight: 700;
        }
        .topbar-user .t-name { font-size: 13px; font-weight: 500; color: #374151; }
        .role-badge {
            font-size: 10px; padding: 2px 7px; border-radius: 20px; font-weight: 600;
        }
        .role-badge.admin { background: #fef2f2; color: #dc2626; }
        .role-badge.user  { background: #eff6ff; color: #2563eb; }

        /* ── Page Content ── */
        .page-content { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .card { border-radius: var(--card-radius); border: 1px solid #e2e8f0; box-shadow: 0 1px 4px rgba(0,0,0,.05); }
        .card-header { border-radius: var(--card-radius) var(--card-radius) 0 0 !important; border-bottom: 1px solid #f1f5f9; padding: 14px 20px; }

        /* ── Stat Cards ── */
        .stat-card {
            border-radius: var(--card-radius); border: none;
            padding: 20px; position: relative; overflow: hidden;
            transition: transform var(--transition), box-shadow var(--transition);
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.12); }
        .stat-card .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px; opacity: .9;
        }
        .stat-card .stat-num { font-size: 28px; font-weight: 700; line-height: 1; margin-top: 12px; }
        .stat-card .stat-label { font-size: 12px; opacity: .75; margin-top: 4px; font-weight: 500; }
        .stat-card .stat-bg-icon {
            position: absolute; right: -10px; bottom: -10px;
            font-size: 80px; opacity: .08;
        }
        .stat-blue   { background: linear-gradient(135deg, #6366f1, #818cf8); color: #fff; }
        .stat-amber  { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; }
        .stat-cyan   { background: linear-gradient(135deg, #06b6d4, #22d3ee); color: #fff; }
        .stat-green  { background: linear-gradient(135deg, #10b981, #34d399); color: #fff; }
        .stat-red    { background: linear-gradient(135deg, #ef4444, #f87171); color: #fff; }

        /* ── Table ── */
        .table th { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: #94a3b8; padding: 10px 16px; border-bottom: 1px solid #f1f5f9; }
        .table td { padding: 12px 16px; vertical-align: middle; border-bottom: 1px solid #f8fafc; }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover { background: #fafbff; }

        /* ── Badges ── */
        .badge { font-weight: 500; font-size: 11px; padding: 4px 9px; border-radius: 6px; }
        .badge-status-pending     { background: #fef3c7; color: #92400e; }
        .badge-status-in_progress { background: #dbeafe; color: #1d4ed8; }
        .badge-status-completed   { background: #d1fae5; color: #065f46; }
        .badge-status-cancelled   { background: #f1f5f9; color: #64748b; }
        .badge-priority-high      { background: #fee2e2; color: #991b1b; }
        .badge-priority-medium    { background: #ffedd5; color: #9a3412; }
        .badge-priority-low       { background: #dcfce7; color: #166534; }

        /* ── Buttons ── */
        .btn { border-radius: 8px; font-size: 13px; font-weight: 500; }
        .btn-primary { background: var(--primary); border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 7px; }

        /* ── Form Controls ── */
        .form-control, .form-select {
            border-radius: 8px; border: 1px solid #e2e8f0;
            font-size: 13.5px; padding: 8px 12px;
            transition: border-color var(--transition), box-shadow var(--transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary); box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .form-label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 5px; }

        /* ── Alerts ── */
        .alert { border-radius: 10px; border: none; font-size: 13.5px; }
        .alert-success { background: #f0fdf4; color: #166534; }
        .alert-danger  { background: #fef2f2; color: #991b1b; }

        /* ── Progress ── */
        .progress { border-radius: 99px; background: #f1f5f9; }
        .progress-bar { border-radius: 99px; }

        /* ── Overdue row ── */
        .overdue-row td { background: #fff5f5 !important; }

        /* ── Sidebar overlay ── */
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 199; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        /* ── Mobile ── */
        @media(max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .main-wrap { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo-icon"><i class="bi bi-check2-all"></i></div>
        <div class="brand-text">
            <div class="brand-name">TaskFlow</div>
            <div class="brand-sub">Pro Dashboard</div>
        </div>
    </div>

    <nav>
        <div class="nav-section-label">Main Menu</div>
        <div class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                <i class="bi bi-kanban"></i> Tasks
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Reports
            </a>
        </div>

        @if(auth()->user()->isAdmin())
        <div class="nav-section-label">Management</div>
        <div class="nav-item">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Users
            </a>
        </div>
        <div class="nav-item">
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> Categories
            </a>
        </div>
        @endif

        <div class="nav-section-label">Account</div>
        <div class="nav-item">
            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> My Profile
            </a>
        </div>
        <div class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link w-100 border-0 bg-transparent text-start">
                    <i class="bi bi-box-arrow-left"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ Str::limit(auth()->user()->name, 18) }}</div>
                <div class="user-role">{{ ucfirst(auth()->user()->role) }}</div>
            </div>
        </div>
    </div>
</aside>

<!-- Main Wrapper -->
<div class="main-wrap">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="topbar-btn d-lg-none border-0" onclick="openSidebar()">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <div class="page-title">@yield('page-title', 'Dashboard')</div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('tasks.create') }}" class="topbar-btn" title="New Task">
                <i class="bi bi-plus-lg"></i>
            </a>
            <a href="{{ route('profile.edit') }}" class="topbar-btn" title="Profile">
                <i class="bi bi-person"></i>
            </a>
            <div class="topbar-user">
                <div class="t-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <span class="t-name d-none d-sm-block">{{ Str::limit(auth()->user()->name, 14) }}</span>
                <span class="role-badge {{ auth()->user()->isAdmin() ? 'admin' : 'user' }}">
                    {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                </span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-4">
                <i class="bi bi-exclamation-circle-fill fs-5"></i>
                <span>{{ session('error') }}</span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-circle-fill fs-5"></i>
                    <strong>Please fix the following errors:</strong>
                </div>
                <ul class="mb-0 ps-4">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebarOverlay').classList.add('open'); }
function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebarOverlay').classList.remove('open'); }
</script>
@yield('scripts')
</body>
</html>
