<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')->latest()->paginate(10);
        return view('admin.category.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('parentCategories'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->only(['name', 'parent_id', 'icon']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        Category::create($data);

        return redirect()->route('admin.category.index')
            ->with('success', 'Thêm danh mục mới thành công!');
    }

    public function show($id)
    {
        $category = Category::with(['parent', 'children', 'products' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->get();

        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->only(['name', 'parent_id', 'icon']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');

        $category->update($data);

        return redirect()->route('admin.category.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.category.index')
            ->with('success', 'Đã chuyển danh mục vào thùng rác!');
    }

    public function trash()
    {
        $trashed = Category::onlyTrashed()->with('parent')->latest()->paginate(10);
        return view('admin.category.trash', compact('trashed'));
    }

    public function restore($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.category.trash')
            ->with('success', 'Đã khôi phục danh mục!');
    }

    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()->route('admin.category.trash')
            ->with('success', 'Đã xóa vĩnh viễn danh mục!');
    }
}
