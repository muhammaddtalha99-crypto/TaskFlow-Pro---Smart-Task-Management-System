<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('user_id', auth()->id())->withCount('tasks')->get();
        return view('categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'color' => 'required|string',
        ]);

        Category::create([
            'user_id' => auth()->id(),
            'name'    => $request->name,
            'color'   => $request->color,
        ]);

        return redirect()->route('categories.index')->with('success', 'Category created!');
    }

    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) abort(403);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted!');
    }
}