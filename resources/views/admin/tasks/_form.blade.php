<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Title <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
               value="{{ old('title', $task->title ?? '') }}" placeholder="Enter task title" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3" placeholder="Describe the task (optional)">{{ old('description', $task->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label">Status <span class="text-danger">*</span></label>
        <select name="status" class="form-select @error('status') is-invalid @enderror">
            @foreach(['pending','in_progress','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ old('status', $task->status ?? 'pending')==$s?'selected':'' }}>
                    {{ ucfirst(str_replace('_',' ',$s)) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Priority <span class="text-danger">*</span></label>
        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
            @foreach(['high','medium','low'] as $p)
                <option value="{{ $p }}" {{ old('priority', $task->priority ?? 'medium')==$p?'selected':'' }}>
                    {{ ucfirst($p) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label">Due Date</label>
        <input type="date" name="due_date" class="form-control"
               value="{{ old('due_date', isset($task->due_date) ? $task->due_date->format('Y-m-d') : '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Category</label>
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
        <label class="form-label">Assign To</label>
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

    <div class="col-md-8">
        <label class="form-label">
            Progress:
            <span id="progress-val" style="color:#6366f1;font-weight:600">{{ old('progress', $task->progress ?? 0) }}%</span>
        </label>
        <input type="range" name="progress" class="form-range" min="0" max="100" step="5"
               value="{{ old('progress', $task->progress ?? 0) }}"
               oninput="document.getElementById('progress-val').textContent = this.value + '%'"
               style="accent-color:#6366f1">
    </div>

    <div class="col-md-4">
        <label class="form-label">Estimated Hours</label>
        <input type="text" name="estimated_hours" class="form-control"
               value="{{ old('estimated_hours', $task->estimated_hours ?? '') }}" placeholder="e.g. 4h, 1.5h">
    </div>
</div>
