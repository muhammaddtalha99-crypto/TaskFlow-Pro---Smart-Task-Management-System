<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Category;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::where('user_id', auth()->id())->with('category');

        if ($request->category)
            $query->where('category_id', $request->category);
        if ($request->status)
            $query->where('status', $request->status);
        if ($request->priority)
            $query->where('priority', $request->priority);
        if ($request->search)
            $query->where('title', 'like', '%' . $request->search . '%');

        $tasks      = $query->latest()->paginate(10);
        $categories = Category::where('user_id', auth()->id())->get();

        // Get reminders - overdue and due soon tasks
        $reminders = Task::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->whereNotNull('due_date')
            ->where('due_date', '<=', now()->addDays(2))
            ->with('category')
            ->get();

        return view('tasks.index', compact('tasks', 'categories', 'reminders'));
    }

    public function create()
    {
        $categories = Category::where('user_id', auth()->id())->get();
        return view('tasks.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date',
            'start_date'  => 'nullable|date',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        $priority = Task::predictPriority($request->due_date);

        Task::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'priority'    => $priority,
            'due_date'    => $request->due_date,
            'start_date'  => $request->start_date,
        ]);

        return redirect()->route('tasks.index')
            ->with('success', 'Task created! Priority auto-predicted: ' . strtoupper($priority));
    }

    public function edit(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $categories = Category::where('user_id', auth()->id())->get();
        return view('tasks.edit', compact('task', 'categories'));
    }

    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'due_date'    => 'nullable|date',
            'start_date'  => 'nullable|date',
            'status'      => 'required|in:pending,in_progress,completed',
        ]);

        $priority = Task::predictPriority($request->due_date);

        $task->update([
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'description' => $request->description,
            'status'      => $request->status,
            'priority'    => $priority,
            'due_date'    => $request->due_date,
            'start_date'  => $request->start_date,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully!');
    }

    public function destroy(Task $task)
    {
        if ($task->user_id !== auth()->id()) abort(403);
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'Task deleted!');
    }
}