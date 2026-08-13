<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Hiển thị trang hồ sơ cá nhân
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem trang cá nhân.');
        }

        $user = Auth::user()->load('addresses');
        return view('client.account.profile', compact('user'));
    }

    /**
     * Cập nhật thông tin cá nhân & Ngân hàng
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'gender' => 'nullable|in:male,female,other',
            'birthday' => 'nullable|date',
            'bank_name' => 'nullable|string|max:255',
            'user_bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ], [
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'avatar.image' => 'File ảnh không hợp lệ.',
            'avatar.max' => 'Dung lượng ảnh tối đa 2MB.',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('users', 'public');
        }

        $user->update($validated);

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Đổi mật khẩu
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu mới tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'is_change_password' => true,
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

    /**
     * Thêm địa chỉ mới (user_addresses)
     */
    public function storeAddress(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'province' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'ward' => 'nullable|string|max:255',
            'street' => 'nullable|string|max:255',
            'address_type' => 'required|in:home,office,other',
            'is_default' => 'nullable|boolean',
        ], [
            'fullname.required' => 'Vui lòng nhập người nhận.',
            'phone_number.required' => 'Vui lòng nhập sđt người nhận.',
            'address.required' => 'Vui lòng nhập chi tiết địa chỉ.',
        ]);

        $isDefault = $request->boolean('is_default');

        if ($isDefault || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $validated['is_default'] = true;
        }

        $validated['user_id'] = $user->id;

        UserAddress::create($validated);

        return back()->with('success', 'Thêm địa chỉ giao hàng thành công!');
    }

    /**
     * Xóa địa chỉ giao hàng
     */
    public function destroyAddress($id)
    {
        $address = UserAddress::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        $address->delete();

        return back()->with('success', 'Đã xóa địa chỉ.');
    }
}
