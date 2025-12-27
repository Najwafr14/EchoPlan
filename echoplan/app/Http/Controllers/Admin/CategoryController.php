<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventCategories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = EventCategories::orderBy('category_id', 'desc')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:eventcategories,category_name',
            'description'   => 'nullable|string',
        ]);

        EventCategories::create($request->all());

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category created successfully');
    }

    public function edit(EventCategories $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, EventCategories $category)
    {
        $request->validate([
            'category_name' => 'required|unique:eventcategories,category_name,' 
                . $category->category_id . ',category_id',
            'description'   => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category updated successfully');
    }

    public function destroy(EventCategories $category)
    {
        $category->delete();

        return redirect()
            ->route('admin.categories')
            ->with('success', 'Category deleted');
    }
}
