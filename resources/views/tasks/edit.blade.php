@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="mb-0 fw-semibold">
                <i class="bi bi-pencil-square me-2 text-primary"></i>Edit Task
            </h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.update', $task) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-medium">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ $task->title }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $task->description }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ $task->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ $task->start_date?->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Due Date</label>
                            <input type="date" name="due_date" class="form-control"
                                value="{{ $task->due_date?->format('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <div class="alert alert-success d-flex align-items-center py-2 mb-3">
                        <i class="bi bi-lightning-charge me-2 fs-5"></i>
                        <small>Current priority: <strong>{{ ucfirst($task->priority) }}</strong> — will re-predict on save.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Update Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection