@extends('layouts.app')
@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">

        {{-- Profile Card --}}
        <div class="card mb-4">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-person-circle" style="color:#6366f1"></i>
                <span style="font-weight:600">Profile Information</span>
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4 mb-4 pb-4" style="border-bottom:1px solid #f1f5f9">
                    <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#818cf8);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:24px;flex-shrink:0">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:18px;font-weight:700;color:#1e293b">{{ $user->name }}</div>
                        <div style="color:#94a3b8;font-size:13px">{{ $user->email }}</div>
                        <span class="badge mt-1" style="{{ $user->isAdmin() ? 'background:#fef2f2;color:#dc2626' : 'background:#eff6ff;color:#2563eb' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Save Changes
                    </button>
                </form>
            </div>
        </div>

        {{-- Password Card --}}
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock" style="color:#f59e0b"></i>
                <span style="font-weight:600">Change Password</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn" style="background:#fffbeb;color:#92400e;border:1px solid #fde68a">
                        <i class="bi bi-key me-1"></i>Update Password
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
