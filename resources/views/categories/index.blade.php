@extends('layouts.app')
@section('content')

<h4 class="fw-semibold mb-4"><i class="bi bi-folder me-2"></i>Categories</h4>

<div class="row g-4">

    {{-- Add category form --}}
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-semibold">Add New Category</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required placeholder="e.g. Work, Study, Health">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Color</label>
                        <input type="color" name="color" class="form-control form-control-color" value="#3B82F6">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-plus-lg me-1"></i>Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Category list --}}
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-semibold">Your Categories</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                @forelse($categories as $cat)
                    <li class="list-group-item d-flex justify-content-between align-items-center px-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="rounded-circle" style="width:14px; height:14px; background:{{ $cat->color }};"></div>
                            <span class="fw-medium">{{ $cat->name }}</span>
                            <span class="badge bg-secondary rounded-pill">{{ $cat->tasks_count }} tasks</span>
                        </div>
                        <form method="POST" action="{{ route('categories.destroy', $cat) }}"
                            onsubmit="return confirm('Delete this category?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </li>
                @empty
                    <li class="list-group-item text-center text-muted py-4">
                        <i class="bi bi-folder-x fs-4 d-block mb-2"></i>
                        No categories yet. Add one!
                    </li>
                @endforelse
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection