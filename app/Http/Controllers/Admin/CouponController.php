<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Danh sách Voucher / Coupon
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        $coupons = $query->latest()->paginate(10)->withQueryString();

        return view('admin.voucher.index', compact('coupons'));
    }

    /**
     * Form tạo Voucher mới
     */
    public function create()
    {
        return view('admin.voucher.create');
    }

    /**
     * Lưu Voucher mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'is_notified' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ], [
            'code.required' => 'Vui lòng nhập mã Voucher.',
            'code.unique' => 'Mã Voucher này đã tồn tại.',
            'discount_value.required' => 'Vui lòng nhập giá trị giảm giá.',
        ]);

        $validated['is_notified'] = $request->boolean('is_notified');

        Coupon::create($validated);

        return redirect()->route('admin.voucher.index')->with('success', 'Tạo mã giảm giá (Voucher) thành công!');
    }

    /**
     * Form sửa Voucher
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.voucher.edit', compact('coupon'));
    }

    /**
     * Cập nhật Voucher
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id)],
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:fixed,percent',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'is_active' => 'required|boolean',
            'is_notified' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $validated['is_notified'] = $request->boolean('is_notified');

        $coupon->update($validated);

        return redirect()->route('admin.voucher.index')->with('success', 'Cập nhật mã giảm giá thành công!');
    }

    /**
     * Xóa mềm Voucher
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.voucher.index')->with('success', 'Đã chuyển Voucher vào thùng rác!');
    }

    /**
     * Thùng rác Voucher
     */
    public function trash()
    {
        $trashed = Coupon::onlyTrashed()->latest('deleted_at')->paginate(10);
        return view('admin.voucher.trash', compact('trashed'));
    }

    /**
     * Khôi phục Voucher
     */
    public function restore($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->restore();

        return redirect()->route('admin.voucher.trash')->with('success', 'Đã khôi phục Voucher!');
    }

    /**
     * Xóa vĩnh viễn Voucher
     */
    public function forceDelete($id)
    {
        $coupon = Coupon::onlyTrashed()->findOrFail($id);
        $coupon->forceDelete();

        return redirect()->route('admin.voucher.trash')->with('success', 'Đã xóa vĩnh viễn Voucher!');
    }
}
