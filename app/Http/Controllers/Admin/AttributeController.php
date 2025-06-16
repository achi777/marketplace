<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoryAttribute;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttributeController extends Controller
{
    private function checkAdminAccess()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Access denied');
        }
    }

    public function index(Request $request)
    {
        $this->checkAdminAccess();
        
        $query = CategoryAttribute::with('category');
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('is_required')) {
            $query->where('is_required', $request->is_required === 'yes');
        }
        
        $attributes = $query->orderBy('sort_order')
                           ->orderBy('name')
                           ->paginate(15);
        
        $categories = Category::orderBy('name')->get();
        
        return view('admin.attributes.index', compact('attributes', 'categories'));
    }

    public function create()
    {
        $this->checkAdminAccess();
        
        $categories = Category::orderBy('name')->get();
        
        return view('admin.attributes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,boolean,color',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        
        // Filter out empty options
        if (isset($data['options'])) {
            $data['options'] = array_filter($data['options'], function($value) {
                return !empty(trim($value));
            });
            $data['options'] = array_values($data['options']); // Re-index array
        }

        CategoryAttribute::create($data);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully.');
    }

    public function show(CategoryAttribute $attribute)
    {
        $this->checkAdminAccess();
        
        $attribute->load('category');
        
        return view('admin.attributes.show', compact('attribute'));
    }

    public function edit(CategoryAttribute $attribute)
    {
        $this->checkAdminAccess();
        
        $categories = Category::orderBy('name')->get();
        
        return view('admin.attributes.edit', compact('attribute', 'categories'));
    }

    public function update(Request $request, CategoryAttribute $attribute)
    {
        $this->checkAdminAccess();
        
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,select,boolean,color',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'is_required' => 'boolean',
            'is_filterable' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $data = $request->all();
        
        // Filter out empty options
        if (isset($data['options'])) {
            $data['options'] = array_filter($data['options'], function($value) {
                return !empty(trim($value));
            });
            $data['options'] = array_values($data['options']); // Re-index array
        }

        $attribute->update($data);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully.');
    }

    public function destroy(CategoryAttribute $attribute)
    {
        $this->checkAdminAccess();
        
        $attribute->delete();

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute deleted successfully.');
    }

    public function toggleStatus(CategoryAttribute $attribute)
    {
        $this->checkAdminAccess();
        
        $attribute->update(['is_filterable' => !$attribute->is_filterable]);
        
        $status = $attribute->is_filterable ? 'enabled for filtering' : 'disabled for filtering';
        return redirect()->back()
            ->with('success', "Attribute {$status} successfully.");
    }

    public function getCategoryAttributes(Category $category)
    {
        $this->checkAdminAccess();
        
        $attributes = $category->attributes()
                              ->orderBy('sort_order')
                              ->orderBy('name')
                              ->get();
        
        return response()->json($attributes);
    }
}