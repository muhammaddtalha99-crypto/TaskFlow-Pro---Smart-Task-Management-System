<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;

class AnalyticsController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $totalTasks      = Task::where('user_id', $userId)->count();
        $completedTasks  = Task::where('user_id', $userId)->where('status', 'completed')->count();
        $pendingTasks    = Task::where('user_id', $userId)->where('status', 'pending')->count();
        $inProgressTasks = Task::where('user_id', $userId)->where('status', 'in_progress')->count();
        $highPriority    = Task::where('user_id', $userId)->where('priority', 'high')->count();
        $completionRate  = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // Streak
        $streak = Task::calculateStreak($userId);

        // Tasks per category with completion rate
        $categories = Category::where('user_id', $userId)->withCount([
            'tasks',
            'tasks as completed_tasks_count' => function ($q) {
                $q->where('status', 'completed');
            }
        ])->get();

        // For charts
        $byCategory = Task::where('user_id', $userId)
            ->with('category')
            ->get()
            ->groupBy(fn($t) => $t->category?->name ?? 'Uncategorized')
            ->map->count();

        // Overdue tasks count
        $overdueTasks = Task::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->count();

        return view('analytics.index', compact(
            'totalTasks', 'completedTasks', 'pendingTasks',
            'inProgressTasks', 'highPriority', 'completionRate',
            'byCategory', 'streak', 'categories', 'overdueTasks'
        ));
    }
}