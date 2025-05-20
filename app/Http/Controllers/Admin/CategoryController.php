<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(): View
{
    $categories = Category::with(['children.products', 'products'])
        ->withCount('children') // Count the number of subcategories
        ->whereNull('parent_id') // Only fetch parent categories
        ->orderBy('name')
        ->paginate(10);

    // Calculate total product count for each parent category
    foreach ($categories as $category) {
        $category->total_products = $category->products->count() + $category->children->sum(function ($child) {
            return $child->products->count();
        });
    }

    return view('admin.categories.index', compact('categories'));
}

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')
            ->orderBy('name')
            ->get();

        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(Category $category): View
    {
        $category->load(['children', 'parent', 'products']);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        // Fetch potential parent categories (excluding itself and its descendants)
        $possibleParents = Category::where('id', '!=', $category->id)
            ->whereNotIn('id', $this->getAllChildrenIds($category))
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'possibleParents'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Prevent circular references
        if (!empty($validated['parent_id'])) {
            if ($validated['parent_id'] == $category->id || 
                in_array($validated['parent_id'], $this->getAllChildrenIds($category))) {
                return redirect()->back()
                    ->with('error', 'Cannot set a category as its own parent or child.')
                    ->withInput();
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.show', $category)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with associated products.');
        }

        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Cannot delete category with subcategories.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Get all descendants IDs of a category.
     */
    private function getAllChildrenIds(Category $category): array
    {
        $ids = [];
        
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $this->getAllChildrenIds($child));
        }
        
        return $ids;
    }
}