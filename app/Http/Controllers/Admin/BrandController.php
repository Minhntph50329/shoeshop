<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\StoreBrandRequest;
use App\Http\Requests\Admin\Brand\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::withCount('products')->latest()->paginate(10);
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(StoreBrandRequest $request)
    {
        $data = $request->only(['name']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        Brand::create($data);

        return redirect()->route('admin.brand.index')
            ->with('success', 'Thêm thương hiệu mới thành công!');
    }

    public function show($id)
    {
        $brand = Brand::with(['products' => function($q) {
            $q->latest();
        }])->findOrFail($id);

        return view('admin.brand.show', compact('brand'));
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $data = $request->only(['name']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = $request->has('is_active');
        $data['is_visible'] = $request->has('is_visible');

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('brands', 'public');
            $data['logo'] = 'storage/' . $path;
        }

        $brand->update($data);

        return redirect()->route('admin.brand.index')
            ->with('success', 'Cập nhật thương hiệu thành công!');
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);
        $brand->delete();

        return redirect()->route('admin.brand.index')
            ->with('success', 'Đã chuyển thương hiệu vào thùng rác!');
    }

    public function trash()
    {
        $trashed = Brand::onlyTrashed()->latest()->paginate(10);
        return view('admin.brand.trash', compact('trashed'));
    }

    public function restore($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->restore();

        return redirect()->route('admin.brand.trash')
            ->with('success', 'Đã khôi phục thương hiệu!');
    }

    public function forceDelete($id)
    {
        $brand = Brand::onlyTrashed()->findOrFail($id);
        $brand->forceDelete();

        return redirect()->route('admin.brand.trash')
            ->with('success', 'Đã xóa vĩnh viễn thương hiệu!');
    }
}
