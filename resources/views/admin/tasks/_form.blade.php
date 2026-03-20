<div class="row g-3">
    <div class="col-12">
        <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $task->title ?? '') }}" placeholder="Task title" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Task description (optional)">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach(['pending','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ old('status', $task->status ?? 'pending')==$s?'selected':'' }}>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Priority <span class="text-danger">*</span></label>
        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
            @foreach(['high','medium','low'] as $p)
                <option value="{{ $p }}" {{ old('priority', $task->priority ?? 'medium')==$p?'selected':'' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Due Date</label>
        <input type="date" name="due_date" class="form-control"
               value="{{ old('due_date', isset($task->due_date) ? $task->due_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Category</label>
        <select name="category_id" class="form-select">
            <option value="">No Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id', $task->category_id ?? '')==$cat->id?'selected':'' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
    </div>

    @if($users->count())
    <div class="col-md-6">
        <label class="form-label fw-semibold">Assign To</label>
        <select name="assigned_to" class="form-select">
            <option value="">Unassigned</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('assigned_to', $task->assigned_to ?? '')==$user->id?'selected':'' }}>
                    {{ $user->name }} ({{ $user->role }})
                </option>
            @endforeach
        </select>
    </div>
    @endif
</div>
