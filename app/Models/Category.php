<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'parent_id',
    ];

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all nested children (recursive relationship).
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Get the products in this category.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all products including those in subcategories.
     */
    public function getAllProductsAttribute()
    {
        $allChildrenIds = $this->getAllChildrenIds();
        $allChildrenIds[] = $this->id;
        
        return Product::whereIn('category_id', $allChildrenIds)->get();
    }

    /**
     * Get an array of all descendant category IDs.
     */
    public function getAllChildrenIds(): array
    {
        $ids = [];
        
        foreach ($this->children as $child) {
            $ids[] = $child->id;
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }
        
        return $ids;
    }

    /**
     * Get the breadcrumb path for this category.
     */
    public function getBreadcrumbAttribute(): array
    {
        $breadcrumb = [];
        $current = $this;
        
        while ($current) {
            array_unshift($breadcrumb, [
                'id' => $current->id,
                'name' => $current->name,
            ]);
            
            $current = $current->parent;
        }
        
        return $breadcrumb;
    }

    /**
     * Get the full hierarchical path as a string.
     */
    public function getPathAttribute(): string
    {
        return collect($this->breadcrumb)->pluck('name')->implode(' > ');
    }

    /**
     * Check if this category has any associated products.
     */
    public function hasProducts(): bool
    {
        return $this->products()->count() > 0;
    }

    /**
     * Check if this category has any subcategories.
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the full hierarchical name with parent(s).
     */
    public function getFullNameAttribute(): string
    {
        if ($this->parent) {
            return $this->parent->getFullNameAttribute() . ' > ' . $this->name;
        }
        
        return $this->name;
    }
}