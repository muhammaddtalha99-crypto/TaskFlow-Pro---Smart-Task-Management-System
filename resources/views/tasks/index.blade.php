@extends('layouts.app')
@section('content')

{{-- REMINDER NOTIFICATIONS --}}
@if($reminders->count() > 0)
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-bell-fill me-2 fs-5"></i>
            <strong>Reminders — {{ $reminders->count() }} task(s) need your attention</strong>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @foreach($reminders as $reminder)
            <div class="d-flex align-items-center gap-2 mt-1">
                @if($reminder->reminder_status === 'overdue')
                    <span class="badge bg-danger">Overdue</span>
                @else
                    <span class="badge bg-warning text-dark">Due Soon</span>
                @endif
                <span class="small">{{ $reminder->title }}</span>
                @if($reminder->due_date)
                    <span class="small text-muted">— Due: {{ $reminder->due_date->format('d M Y') }}</span>
                @endif
            </div>
        @endforeach
    </div>
@endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-semibold">
        <i class="bi bi-list-check me-2 text-primary"></i>My Health Tasks
    </h4>
    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>New Task
    </a>
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-medium">Search</label>
                <input type="text" name="search" class="form-control form-control-sm"
                    value="{{ request('search') }}" placeholder="Search tasks...">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Category</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-medium">Priority</label>
                <select name="priority" class="form-select form-select-sm">
                    <option value="">All</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="bi bi-funnel me-1"></i>Filter
                </button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg me-1"></i>Clear
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tasks table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Start Date</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($tasks as $task)
                    <tr class="{{ $task->reminder_status === 'overdue' ? 'table-danger' : ($task->reminder_status === 'due_soon' ? 'table-warning' : '') }}">
                        <td class="fw-medium">
                            {{ $task->title }}
                            @if($task->reminder_status === 'overdue')
                                <span class="badge bg-danger ms-1">Overdue</span>
                            @elseif($task->reminder_status === 'due_soon')
                                <span class="badge bg-warning text-dark ms-1">Due Soon</span>
                            @endif
                        </td>
                        <td>
                            @if($task->category)
                                <span class="badge rounded-pill" style="background-color:{{ $task->category->color }}">
                                    {{ $task->category->name }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            @if($task->priority == 'high')
                                <span class="badge bg-danger">
                                    <i class="bi bi-exclamation-triangle me-1"></i>High
                                </span>
                            @elseif($task->priority == 'medium')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-dash-circle me-1"></i>Medium
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Low
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($task->status == 'completed')
                                <span class="badge bg-success">
                                    <i class="bi bi-check2 me-1"></i>Completed
                                </span>
                            @elseif($task->status == 'in_progress')
                                <span class="badge bg-primary">
                                    <i class="bi bi-arrow-repeat me-1"></i>In Progress
                                </span>
                            @else
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $task->start_date ? $task->start_date->format('d M Y') : '—' }}
                        </td>
                        <td class="text-muted small">
                            {{ $task->due_date ? $task->due_date->format('d M Y') : '—' }}
                        </td>
                        <td>
                            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}"
                                class="d-inline" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            No tasks found. Create your first health task!
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-0">
        {{ $tasks->withQueryString()->links() }}
    </div>
</div>

@endsection