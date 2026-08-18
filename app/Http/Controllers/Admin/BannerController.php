<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Requests\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\Admin\Banner\UpdateBannerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Danh sách Banner
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('subtitle', 'like', '%' . $request->search . '%');
        }

        $banners = $query->orderBy('sort_order', 'asc')->latest()->paginate(10)->withQueryString();

        return view('admin.banner.index', compact('banners'));
    }

    /**
     * Form tạo Banner mới
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Lưu Banner mới
     */
    public function store(StoreBannerRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        Banner::create($validated);

        return redirect()->route('admin.banner.index')->with('success', 'Thêm Banner mới thành công!');
    }

    /**
     * Form sửa Banner
     */
    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.edit', compact('banner'));
    }

    /**
     * Cập nhật Banner
     */
    public function update(UpdateBannerRequest $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($banner->image && str_contains($banner->image, 'storage/')) {
                Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
            }
            $path = $request->file('image')->store('banners', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        $banner->update($validated);

        return redirect()->route('admin.banner.index')->with('success', 'Cập nhật Banner thành công!');
    }

    /**
     * Xóa mềm Banner
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('admin.banner.index')->with('success', 'Đã chuyển Banner vào thùng rác!');
    }

    /**
     * Thùng rác Banner
     */
    public function trash()
    {
        $trashed = Banner::onlyTrashed()->latest('deleted_at')->paginate(10);
        return view('admin.banner.trash', compact('trashed'));
    }

    /**
     * Khôi phục Banner
     */
    public function restore($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);
        $banner->restore();

        return redirect()->route('admin.banner.trash')->with('success', 'Đã khôi phục Banner!');
    }

    /**
     * Xóa vĩnh viễn Banner
     */
    public function forceDelete($id)
    {
        $banner = Banner::onlyTrashed()->findOrFail($id);

        if ($banner->image && str_contains($banner->image, 'storage/')) {
            Storage::disk('public')->delete(str_replace('storage/', '', $banner->image));
        }

        $banner->forceDelete();

        return redirect()->route('admin.banner.trash')->with('success', 'Đã xóa vĩnh viễn Banner!');
    }
}
