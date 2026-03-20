@extends('layouts.app')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0 fw-bold" style="color:#1e293b">Team Members</h5>
        <p class="mb-0 mt-1" style="color:#94a3b8;font-size:13px">Manage user accounts and permissions</p>
    </div>
    <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-person-plus"></i> Add User
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Assigned</th>
                        <th>Joined</th>
                        <th style="width:80px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:13px;flex-shrink:0">
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <span style="font-weight:500;color:#1e293b">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color:#64748b;font-size:13px">{{ $user->email }}</td>
                        <td>
                            <span class="badge" style="{{ $user->role==='admin' ? 'background:#fef2f2;color:#dc2626' : 'background:#eff6ff;color:#2563eb' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="{{ $user->is_active ? 'background:#f0fdf4;color:#16a34a' : 'background:#f1f5f9;color:#64748b' }}">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td style="color:#64748b;font-size:13px;text-align:center">{{ $user->tasks_count }}</td>
                        <td style="color:#64748b;font-size:13px;text-align:center">{{ $user->assigned_tasks_count }}</td>
                        <td style="color:#94a3b8;font-size:13px">{{ $user->created_at->format('M d, Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-icon" style="background:#eff6ff;color:#6366f1;border:none" title="Edit">
                                    <i class="bi bi-pencil" style="font-size:13px"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete {{ $user->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-icon" style="background:#fef2f2;color:#ef4444;border:none" title="Delete">
                                        <i class="bi bi-trash" style="font-size:13px"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-5" style="color:#94a3b8">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="card-footer bg-white" style="border-top:1px solid #f1f5f9">{{ $users->links() }}</div>
    @endif
</div>
@endsection
