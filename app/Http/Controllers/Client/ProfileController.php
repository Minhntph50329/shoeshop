<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use App\Http\Requests\Client\Profile\UpdateProfileRequest;
use App\Http\Requests\Client\Profile\UpdatePasswordRequest;
use App\Http\Requests\Client\Profile\StoreAddressRequest;
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
    public function updateProfile(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

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
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

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
    public function storeAddress(StoreAddressRequest $request)
    {
        $user = Auth::user();

        $validated = $request->validated();

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
