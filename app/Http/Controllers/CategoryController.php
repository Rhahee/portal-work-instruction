<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index() { return view('manage.categories.index', ['categories' => Category::withCount('workInstructions')->orderBy('name')->get()]); }
    public function edit(Category $category) { return view('manage.categories.edit', compact('category')); }
    public function store(Request $request) { $data = $request->validate(['name' => ['required', 'string', 'max:80', 'unique:categories'], 'type' => ['required', 'in:general,it'], 'description' => ['nullable', 'string', 'max:300']]); $data['slug'] = Str::slug($data['name']); Category::create($data); return back()->with('status', 'Kategori dibuat.'); }
    public function update(Request $request, Category $category) { $data = $request->validate(['name' => ['required', 'string', 'max:80', 'unique:categories,name,'.$category->id], 'type' => ['required', 'in:general,it'], 'description' => ['nullable', 'string', 'max:300']]); $data['slug'] = Str::slug($data['name']); $category->update($data); return back()->with('status', 'Kategori diperbarui.'); }
    public function destroy(Category $category) { abort_if($category->workInstructions()->exists(), 422, 'Kategori yang masih dipakai tidak dapat dihapus.'); $category->delete(); return back()->with('status', 'Kategori dihapus.'); }
}
