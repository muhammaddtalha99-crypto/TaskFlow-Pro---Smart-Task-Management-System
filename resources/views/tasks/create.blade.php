@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-7">

        <div class="d-flex align-items-center mb-4">
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h4 class="mb-0 fw-semibold">
                <i class="bi bi-plus-circle me-2 text-primary"></i>Create New Health Task
            </h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('tasks.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-medium">Task Title <span class="text-danger">*</span></label>
                        <input type="text" name="title"
                            class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}"
                            placeholder="e.g. Morning Workout, Drink 8 glasses of water">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                            placeholder="Details about this health activity...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div class="form-text">
                            <a href="{{ route('categories.index') }}">+ Add new category</a>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Start Date</label>
                            <input type="date" name="start_date" class="form-control"
                                value="{{ old('start_date') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Due Date</label>
                            <input type="date" name="due_date" class="form-control"
                                value="{{ old('due_date') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Status</label>
                        <select name="status" class="form-select">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>

                    <div class="alert alert-info d-flex align-items-center py-2 mb-3">
                        <i class="bi bi-magic me-2 fs-5"></i>
                        <div>
                            <strong>Auto Priority Prediction</strong><br>
                            <small>Priority (High / Medium / Low) will be automatically calculated based on your due date.</small>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i>Create Task
                        </button>
                        <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

@endsection