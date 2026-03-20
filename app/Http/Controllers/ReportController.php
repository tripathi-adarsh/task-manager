<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $baseQuery = $user->isAdmin()
            ? Task::query()
            : Task::where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
            });

        // Status breakdown
        $statusData = (clone $baseQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Priority breakdown
        $priorityData = (clone $baseQuery)
            ->selectRaw('priority, count(*) as count')
            ->groupBy('priority')
            ->pluck('count', 'priority');

        // Tasks per category
        $categoryData = Category::withCount(['tasks' => function($q) use ($user, $baseQuery) {
            if (!$user->isAdmin()) {
                $q->where(function($q2) use ($user) {
                    $q2->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
                });
            }
        }])->get();

        // Monthly task creation (last 6 months)
        $monthlyData = (clone $baseQuery)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        // Overdue tasks
        $overdueTasks = (clone $baseQuery)
            ->with(['assignee', 'category'])
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->orderBy('due_date')
            ->get();

        // Top users by task completion (admin only)
        $userStats = $user->isAdmin()
            ? User::withCount([
                'tasks as total_tasks',
                'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed'),
              ])->having('total_tasks', '>', 0)->orderByDesc('total_tasks')->limit(10)->get()
            : collect();

        return view('admin.reports.index', compact(
            'statusData', 'priorityData', 'categoryData',
            'monthlyData', 'overdueTasks', 'userStats'
        ));
    }
}
