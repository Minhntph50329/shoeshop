<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Http\Requests\Admin\Settings\UpdateWebsiteSettingRequest;
use App\Http\Requests\Admin\Settings\UpdateStoreSettingRequest;
use App\Http\Requests\Admin\Settings\UpdatePaymentSettingRequest;
use App\Http\Requests\Admin\Settings\UpdateShippingSettingRequest;
use App\Http\Requests\Admin\Settings\UpdateNotificationSettingRequest;
use App\Http\Requests\Admin\Settings\UpdateAccountSettingRequest;

class SettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function website()
    {
        return view('admin.settings.website');
    }

    public function websiteSave(UpdateWebsiteSettingRequest $request)
    {
        Setting::set('site_name', $request->site_name);
        Setting::set('site_email', $request->site_email);
        Setting::set('site_phone', $request->site_phone);
        Setting::set('site_address', $request->site_address);
        Setting::set('site_description', $request->site_description);
        Setting::set('maintenance_mode', $request->has('maintenance_mode') ? '1' : '0', 'boolean');
        Setting::set('maintenance_message', $request->maintenance_message);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('settings', 'public');
            Setting::set('logo', 'storage/' . $path);
        }

        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('settings', 'public');
            Setting::set('favicon', 'storage/' . $path);
        }

        return redirect()->back()->with('success', 'Cập nhật cài đặt website thành công.');
    }

    public function store()
    {
        return view('admin.settings.store');
    }

    public function storeSave(UpdateStoreSettingRequest $request)
    {
        Setting::set('currency', $request->currency);
        Setting::set('products_per_page', $request->products_per_page, 'number');
        Setting::set('allow_out_of_stock', $request->has('allow_out_of_stock') ? '1' : '0', 'boolean');
        Setting::set('allow_cancel_order', $request->has('allow_cancel_order') ? '1' : '0', 'boolean');
        Setting::set('cancel_order_hours', $request->cancel_order_hours, 'number');
        Setting::set('free_shipping_min', $request->free_shipping_min, 'number');

        return redirect()->back()->with('success', 'Cập nhật cài đặt bán hàng thành công.');
    }

    public function payment()
    {
        return view('admin.settings.payment');
    }

    public function paymentSave(UpdatePaymentSettingRequest $request)
    {
        Setting::set('payment_cod', $request->has('payment_cod') ? '1' : '0', 'boolean');
        Setting::set('payment_bank', $request->has('payment_bank') ? '1' : '0', 'boolean');
        Setting::set('payment_vnpay', $request->has('payment_vnpay') ? '1' : '0', 'boolean');
        
        Setting::set('bank_name', $request->bank_name);
        Setting::set('bank_account_number', $request->bank_account_number);
        Setting::set('bank_account_name', $request->bank_account_name);
        Setting::set('bank_transfer_content', $request->bank_transfer_content);

        return redirect()->back()->with('success', 'Cập nhật cài đặt thanh toán thành công.');
    }

    public function shipping()
    {
        return view('admin.settings.shipping');
    }

    public function shippingSave(UpdateShippingSettingRequest $request)
    {
        Setting::set('shipping_fee', $request->shipping_fee, 'number');
        Setting::set('free_shipping_min', $request->free_shipping_min, 'number');
        Setting::set('allow_national_shipping', $request->has('allow_national_shipping') ? '1' : '0', 'boolean');
        Setting::set('shipping_time', $request->shipping_time);

        return redirect()->back()->with('success', 'Cập nhật cài đặt vận chuyển thành công.');
    }

    public function notification()
    {
        return view('admin.settings.notification');
    }

    public function notificationSave(UpdateNotificationSettingRequest $request)
    {
        Setting::set('notify_new_order', $request->has('notify_new_order') ? '1' : '0', 'boolean');
        Setting::set('notify_return', $request->has('notify_return') ? '1' : '0', 'boolean');
        Setting::set('notify_review', $request->has('notify_review') ? '1' : '0', 'boolean');
        Setting::set('notify_low_stock', $request->has('notify_low_stock') ? '1' : '0', 'boolean');
        Setting::set('notify_new_customer', $request->has('notify_new_customer') ? '1' : '0', 'boolean');
        Setting::set('notify_contact', $request->has('notify_contact') ? '1' : '0', 'boolean');
        Setting::set('notification_email', $request->notification_email);

        return redirect()->back()->with('success', 'Cập nhật cài đặt thông báo thành công.');
    }

    public function account()
    {
        return view('admin.settings.account');
    }

    public function accountSave(UpdateAccountSettingRequest $request)
    {
        $user = auth()->user();

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.']);
            }
            $user->password = Hash::make($request->new_password);
        }

        if ($request->filled('fullname')) {
            $user->fullname = $request->fullname;
        }
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('phone')) {
            $user->phone = $request->phone;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = 'storage/' . $path;
        }

        $user->save();

        return redirect()->back()->with('success', 'Cập nhật tài khoản thành công.');
    }
}
