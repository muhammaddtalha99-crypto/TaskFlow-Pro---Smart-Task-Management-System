@extends('layouts.app')
@section('content')

<h4 class="fw-semibold mb-4">
    <i class="bi bi-graph-up me-2 text-primary"></i>Health & Fitness Dashboard
</h4>

{{-- Top stat cards --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-primary">{{ $totalTasks }}</div>
                <div class="text-muted small">Total Tasks</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-success">{{ $completedTasks }}</div>
                <div class="text-muted small">Completed</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-warning">{{ $pendingTasks }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-danger">{{ $overdueTasks }}</div>
                <div class="text-muted small">Overdue</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold text-danger">{{ $highPriority }}</div>
                <div class="text-muted small">High Priority</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center h-100">
            <div class="card-body py-3">
                <div class="fs-2 fw-bold" style="color:#8B5CF6;">{{ $completionRate }}%</div>
                <div class="text-muted small">Completion</div>
            </div>
        </div>
    </div>
</div>

{{-- Streak card --}}
<div class="card border-0 shadow-sm mb-4"
     style="background: linear-gradient(135deg, #1a5276, #2e86c1); color:white;">
    <div class="card-body d-flex align-items-center gap-3 py-3">
        <i class="bi bi-fire fs-1 text-warning"></i>
        <div>
            <div class="fs-3 fw-bold">{{ $streak }} Day{{ $streak != 1 ? 's' : '' }} Streak</div>
            <div class="opacity-75 small">
                @if($streak == 0)
                    Complete a task today to start your streak!
                @elseif($streak < 3)
                    Good start! Keep going!
                @elseif($streak < 7)
                    Great consistency! You are doing well!
                @else
                    Amazing streak! You are a health champion!
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Overall progress bar --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
            <span class="fw-medium">Overall Completion Rate</span>
            <span class="text-muted small">{{ $completedTasks }} / {{ $totalTasks }} tasks</span>
        </div>
        <div class="progress mb-1" style="height:14px; border-radius:10px;">
            <div class="progress-bar bg-success" style="width:{{ $completionRate }}%; border-radius:10px;">
                {{ $completionRate }}%
            </div>
        </div>
    </div>
</div>

{{-- Category progress bars --}}
@if($categories->count() > 0)
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 pt-3">
        <h6 class="mb-0 fw-semibold">Progress by Category</h6>
    </div>
    <div class="card-body">
        @foreach($categories as $cat)
            @php
                $rate = $cat->tasks_count > 0
                    ? round(($cat->completed_tasks_count / $cat->tasks_count) * 100)
                    : 0;
            @endphp
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="small fw-medium">
                        <span class="rounded-circle d-inline-block me-1"
                              style="width:10px;height:10px;background:{{ $cat->color }};"></span>
                        {{ $cat->name }}
                    </span>
                    <span class="small text-muted">
                        {{ $cat->completed_tasks_count }} / {{ $cat->tasks_count }} — {{ $rate }}%
                    </span>
                </div>
                <div class="progress" style="height:10px; border-radius:8px;">
                    <div class="progress-bar" role="progressbar"
                         style="width:{{ $rate }}%; background-color:{{ $cat->color }}; border-radius:8px;">
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Charts --}}
<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-semibold">Tasks by Status</h6>
            </div>
            <div class="card-body">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="mb-0 fw-semibold">Tasks by Category</h6>
            </div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Completed', 'In Progress', 'Pending'],
        datasets: [{
            data: [{{ $completedTasks }}, {{ $inProgressTasks }}, {{ $pendingTasks }}],
            backgroundColor: ['#198754', '#0d6efd', '#ffc107'],
            borderWidth: 0
        }]
    },
    options: {
        plugins: { legend: { position: 'bottom' } },
        cutout: '65%'
    }
});

new Chart(document.getElementById('categoryChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($byCategory->keys()) !!},
        datasets: [{
            label: 'Total Tasks',
            data: {!! json_encode($byCategory->values()) !!},
            backgroundColor: '#0d6efd',
            borderRadius: 6,
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>

@endsection