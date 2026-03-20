<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $taskQuery = $user->isAdmin() ? Task::query() : Task::where(function($q) use ($user) {
            $q->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
        });

        $stats = [
            'total'       => (clone $taskQuery)->count(),
            'pending'     => (clone $taskQuery)->where('status', 'pending')->count(),
            'in_progress' => (clone $taskQuery)->where('status', 'in_progress')->count(),
            'completed'   => (clone $taskQuery)->where('status', 'completed')->count(),
            'overdue'     => (clone $taskQuery)->where('status', '!=', 'completed')
                                ->whereNotNull('due_date')->where('due_date', '<', now())->count(),
        ];

        $recentTasks = (clone $taskQuery)->with(['category', 'assignee'])
            ->orderByDesc('created_at')->limit(5)->get();

        $upcomingTasks = (clone $taskQuery)->with(['category', 'assignee'])
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')->where('due_date', '>=', now())
            ->orderBy('due_date')->limit(5)->get();

        $totalUsers = $user->isAdmin() ? User::count() : null;
        $categories = Category::withCount('tasks')->get();

        return view('admin.dashboard', compact('stats', 'recentTasks', 'upcomingTasks', 'totalUsers', 'categories'));
    }
}
