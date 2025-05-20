<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('stock_quantity', '>', 0);

        // Filter by category (parent or child)
        if ($request->has('category') && $request->category) {
            $categoryId = $request->category;

            // Check if the selected category is a parent category
            $category = Category::find($categoryId);
            if ($category && $category->children->isNotEmpty()) {
                // If it's a parent category, filter by its child categories
                $childCategoryIds = $category->children->pluck('id')->toArray();
                $query->whereIn('category_id', $childCategoryIds);
            } else {
                // Otherwise, filter by the selected category directly
                $query->where('category_id', $categoryId);
            }
        }

        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $products = $query->paginate(9);

        // Fetch parent categories with their children
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category.
     */
    public function byCategory(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('products.by_category', compact('products', 'category', 'categories'));
    }
}