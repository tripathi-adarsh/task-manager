@extends('layouts.app')
@section('title', 'Categories')
@section('page-title', 'Categories')

@section('content')
<div class="row g-4">
    {{-- Add Form --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-plus-circle" style="color:#6366f1"></i>
                <span style="font-weight:600">Add Category</span>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name') }}" placeholder="Category name" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Color</label>
                        <div class="d-flex align-items-center gap-3">
                            <input type="color" name="color" class="form-control form-control-color"
                                   value="{{ old('color', '#6366f1') }}" style="width:48px;height:40px;border-radius:8px;cursor:pointer">
                            <span style="color:#94a3b8;font-size:13px">Pick a color</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i>Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- List --}}
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white d-flex align-items-center gap-2">
                <i class="bi bi-tags" style="color:#10b981"></i>
                <span style="font-weight:600">All Categories</span>
                <span class="badge ms-auto" style="background:#f1f5f9;color:#64748b">{{ $categories->total() }}</span>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Color</th>
                            <th>Name</th>
                            <th>Tasks</th>
                            <th style="width:80px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                        <tr>
                            <td>
                                <span style="display:inline-block;width:28px;height:28px;border-radius:8px;background:{{ $category->color }}"></span>
                            </td>
                            <td style="font-weight:500;color:#1e293b">{{ $category->name }}</td>
                            <td>
                                <span class="badge" style="background:#f1f5f9;color:#64748b">{{ $category->tasks_count }} tasks</span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <button class="btn btn-icon" style="background:#eff6ff;color:#6366f1;border:none"
                                            onclick="editCategory({{ $category->id }}, '{{ $category->name }}', '{{ $category->color }}')">
                                        <i class="bi bi-pencil" style="font-size:13px"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                          onsubmit="return confirm('Delete category? Tasks will be uncategorized.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-icon" style="background:#fef2f2;color:#ef4444;border:none">
                                            <i class="bi bi-trash" style="font-size:13px"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5" style="color:#94a3b8">No categories yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($categories->hasPages())
            <div class="card-footer bg-white" style="border-top:1px solid #f1f5f9">{{ $categories->links() }}</div>
            @endif
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:14px;border:1px solid #e2e8f0">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9">
                <h6 class="modal-title" style="font-weight:600">Edit Category</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" id="editName" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" id="editColor" class="form-control form-control-color" style="width:48px;height:40px;border-radius:8px">
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #f1f5f9">
                    <button type="button" class="btn btn-sm" style="background:#f1f5f9;color:#64748b" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editCategory(id, name, color) {
    document.getElementById('editForm').action = `/categories/${id}`;
    document.getElementById('editName').value = name;
    document.getElementById('editColor').value = color;
    new bootstrap.Modal(document.getElementById('editModal')).show();
}
</script>
@endsection
