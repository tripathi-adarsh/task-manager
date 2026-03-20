<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    private function taskQuery()
    {
        $user = auth()->user();
        return $user->isAdmin()
            ? Task::query()
            : Task::where(function($q) use ($user) {
                $q->where('user_id', $user->id)->orWhere('assigned_to', $user->id);
            });
    }

    public function index(Request $request)
    {
        $query = $this->taskQuery()->with(['category', 'assignee', 'creator']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }
        if ($request->status)   $query->where('status', $request->status);
        if ($request->priority) $query->where('priority', $request->priority);
        if ($request->category) $query->where('category_id', $request->category);

        $tasks      = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $categories = Category::all();
        $users      = auth()->user()->isAdmin() ? User::where('is_active', true)->get() : collect();

        return view('admin.tasks.index', compact('tasks', 'categories', 'users'));
    }

    public function create()
    {
        $categories = Category::all();
        $users      = auth()->user()->isAdmin() ? User::where('is_active', true)->get() : collect();
        return view('admin.tasks.create', compact('categories', 'users'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed,cancelled',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $data['user_id'] = auth()->id();
        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        $this->authorizeTask($task);
        $categories = Category::all();
        $users      = auth()->user()->isAdmin() ? User::where('is_active', true)->get() : collect();
        return view('admin.tasks.edit', compact('task', 'categories', 'users'));
    }

    public function update(Request $request, Task $task)
    {
        $this->authorizeTask($task);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:pending,in_progress,completed,cancelled',
            'priority'    => 'required|in:low,medium,high',
            'due_date'    => 'nullable|date',
            'category_id' => 'nullable|exists:categories,id',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $task->update($data);
        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $this->authorizeTask($task);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->authorizeTask($task);
        $task->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    private function authorizeTask(Task $task)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && $task->user_id !== $user->id && $task->assigned_to !== $user->id) {
            abort(403);
        }
    }
}
