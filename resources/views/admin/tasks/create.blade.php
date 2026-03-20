@extends('layouts.app')
@section('title', 'Create Task')
@section('page-title', 'Create Task')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex align-items-center gap-3 mb-4">
            <a href="{{ route('tasks.index') }}" class="btn btn-icon" style="background:#f1f5f9;color:#64748b;border:none">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h5 class="mb-0 fw-bold" style="color:#1e293b">New Task</h5>
                <p class="mb-0 mt-1" style="color:#94a3b8;font-size:13px">Fill in the details below</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-4">
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    @include('admin.tasks._form')
                    <div class="d-flex gap-2 mt-4 pt-2" style="border-top:1px solid #f1f5f9">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i>Create Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn" style="background:#f1f5f9;color:#64748b">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
