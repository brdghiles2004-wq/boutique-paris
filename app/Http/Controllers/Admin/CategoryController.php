<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::with(['parent', 'children'])
            ->orderBy('parent_id')
            ->orderBy('order')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        $parents = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(4);
        $validated['is_active'] = $request->boolean('is_active', true);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie créée ✅');
    }

    public function edit(Category $category): View
    {
        $parents = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'order' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Catégorie mise à jour ✅');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Catégorie supprimée');
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', compact('category'));
    }
}