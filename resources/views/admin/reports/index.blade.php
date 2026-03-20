@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')

@section('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endsection

@section('content')
<div class="row g-4">

    {{-- Status Donut --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-pie-chart-fill" style="color:#6366f1"></i>
                <span style="font-weight:600">Tasks by Status</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center py-4">
                <canvas id="statusChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- Priority Bar --}}
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-bar-chart-fill" style="color:#f59e0b"></i>
                <span style="font-weight:600">Tasks by Priority</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center py-4">
                <canvas id="priorityChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- Monthly Trend --}}
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-graph-up-arrow" style="color:#10b981"></i>
                <span style="font-weight:600">Monthly Trend</span>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center py-4">
                <canvas id="monthlyChart" style="max-height:220px"></canvas>
            </div>
        </div>
    </div>

    {{-- Category Breakdown --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-tags-fill" style="color:#06b6d4"></i>
                <span style="font-weight:600">Tasks by Category</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>Category</th><th>Tasks</th><th>Share</th></tr>
                    </thead>
                    <tbody>
                        @php $total = $categoryData->sum('tasks_count') ?: 1; @endphp
                        @foreach($categoryData as $cat)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span style="width:10px;height:10px;border-radius:50%;background:{{ $cat->color }};display:inline-block;flex-shrink:0"></span>
                                    <span style="font-size:13.5px;color:#374151">{{ $cat->name }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b;font-size:13px">{{ $cat->tasks_count }}</td>
                            <td style="min-width:120px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:6px">
                                        <div class="progress-bar" style="width:{{ round($cat->tasks_count/$total*100) }}%;background:{{ $cat->color }}"></div>
                                    </div>
                                    <span style="font-size:11px;color:#94a3b8">{{ round($cat->tasks_count/$total*100) }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Overdue Tasks --}}
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-exclamation-triangle-fill" style="color:#ef4444"></i>
                <span style="font-weight:600">Overdue Tasks</span>
                @if($overdueTasks->count())
                <span class="badge ms-auto" style="background:#fef2f2;color:#dc2626">{{ $overdueTasks->count() }}</span>
                @endif
            </div>
            <div style="max-height:300px;overflow-y:auto">
                @forelse($overdueTasks as $task)
                <div class="d-flex align-items-center justify-content-between px-4 py-3" style="border-bottom:1px solid #f8fafc">
                    <div>
                        <a href="{{ route('tasks.edit', $task) }}" class="text-decoration-none" style="font-weight:500;color:#1e293b;font-size:13.5px">{{ Str::limit($task->title, 35) }}</a>
                        <div style="color:#94a3b8;font-size:12px">{{ $task->assignee ? $task->assignee->name : 'Unassigned' }}</div>
                    </div>
                    <span class="badge badge-priority-high">{{ $task->due_date->format('M d') }}</span>
                </div>
                @empty
                <div class="text-center py-5" style="color:#94a3b8;font-size:13px">
                    <i class="bi bi-check-circle d-block fs-2 mb-2" style="color:#10b981"></i>
                    No overdue tasks!
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- User Performance (Admin) --}}
    @if($userStats->count())
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-people-fill" style="color:#6366f1"></i>
                <span style="font-weight:600">User Performance</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr><th>User</th><th>Total</th><th>Completed</th><th>Completion Rate</th></tr>
                    </thead>
                    <tbody>
                        @foreach($userStats as $u)
                        @php $rate = $u->total_tasks > 0 ? round($u->completed_tasks / $u->total_tasks * 100) : 0; @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;color:#fff;font-size:12px;font-weight:700">
                                        {{ strtoupper(substr($u->name,0,1)) }}
                                    </div>
                                    <span style="font-weight:500;color:#1e293b">{{ $u->name }}</span>
                                </div>
                            </td>
                            <td style="color:#64748b">{{ $u->total_tasks }}</td>
                            <td style="color:#64748b">{{ $u->completed_tasks }}</td>
                            <td style="min-width:180px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height:8px">
                                        <div class="progress-bar" style="width:{{ $rate }}%;background:{{ $rate >= 70 ? '#10b981' : ($rate >= 40 ? '#f59e0b' : '#ef4444') }}"></div>
                                    </div>
                                    <span style="font-size:12px;color:#64748b;min-width:32px">{{ $rate }}%</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
const statusData   = @json($statusData);
const priorityData = @json($priorityData);
const monthlyData  = @json($monthlyData);

const chartDefaults = { font: { family: 'Inter, sans-serif' } };
Chart.defaults.font.family = 'Inter, sans-serif';

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(s => s.replace('_',' ').replace(/\b\w/g,c=>c.toUpperCase())),
        datasets: [{ data: Object.values(statusData), backgroundColor: ['#fef3c7','#dbeafe','#d1fae5','#f1f5f9'], borderColor: ['#f59e0b','#3b82f6','#10b981','#94a3b8'], borderWidth: 2 }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { padding: 16, font: { size: 12 } } } }, cutout: '65%' }
});

new Chart(document.getElementById('priorityChart'), {
    type: 'bar',
    data: {
        labels: ['High', 'Medium', 'Low'],
        datasets: [{ data: Object.values(priorityData), backgroundColor: ['#fee2e2','#ffedd5','#dcfce7'], borderColor: ['#ef4444','#f97316','#22c55e'], borderWidth: 2, borderRadius: 8 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false }, ticks: { font: { size: 12 } } } } }
});

new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: Object.keys(monthlyData),
        datasets: [{ label: 'Tasks', data: Object.values(monthlyData), borderColor: '#6366f1', backgroundColor: 'rgba(99,102,241,.08)', fill: true, tension: 0.4, pointBackgroundColor: '#6366f1', pointRadius: 4 }]
    },
    options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 11 } }, grid: { color: '#f1f5f9' } }, x: { grid: { display: false }, ticks: { font: { size: 11 } } } } }
});
</script>
@endsection
