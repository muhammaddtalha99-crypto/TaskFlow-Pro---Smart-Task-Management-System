<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskFlow Pro — Health & Fitness</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#1a5276;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="{{ route('tasks.index') }}">
            <i class="bi bi-heart-pulse-fill me-2 text-danger"></i>TaskFlow Pro
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}"
                       href="{{ route('tasks.index') }}">
                        <i class="bi bi-list-check me-1"></i>My Tasks
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                       href="{{ route('categories.index') }}">
                        <i class="bi bi-folder2 me-1"></i>Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('analytics.*') ? 'active' : '' }}"
                       href="{{ route('analytics.index') }}">
                        <i class="bi bi-graph-up me-1"></i>Dashboard
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <span class="nav-link text-light opacity-75">
                        <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>