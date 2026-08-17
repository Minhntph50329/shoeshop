<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\LoginNotification;
use App\Mail\VerifyRegistration;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('client.authentication.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Địa chỉ email không hợp lệ.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && $user->status === 'locked') {
            $reason = $user->reason_lock ? ' Lý do: ' . $user->reason_lock : '';
            return back()->withInput()->withErrors([
                'email' => 'Tài khoản của bạn đã bị khóa.' . $reason,
            ]);
        }

        if ($user && is_null($user->email_verified_at)) {
            return back()->withInput()->withErrors([
                'email' => 'Tài khoản của bạn chưa được xác nhận. Vui lòng kiểm tra email để xác nhận.',
            ]);
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            try {
                Mail::to(Auth::user()->email)->send(new LoginNotification(
                    Auth::user(),
                    now()->format('d/m/Y H:i:s'),
                    $request->ip(),
                    $request->userAgent()
                ));
            } catch (\Exception $e) {
                // Log error if needed
            }

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', 'Đăng nhập trang quản trị thành công!');
            }

            return redirect()->intended(route('home'))->with('success', 'Đăng nhập thành công!');
        }

        return back()->withInput()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
        ]);
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('client.authentication.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'fullname.required' => 'Vui lòng nhập họ và tên.',
            'email.required' => 'Vui lòng nhập địa chỉ email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = User::create([
            'fullname' => $validated['fullname'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'client',
            'status' => 'active',
        ]);

        $verifyUrl = route('register.verify', ['id' => $user->id, 'hash' => sha1($user->email)]);
        try {
            Mail::to($user->email)->send(new VerifyRegistration($user, $verifyUrl));
        } catch (\Exception $e) {
            // Log error
        }

        return redirect()->route('login')->with('success', 'Đăng ký thành công! Vui lòng kiểm tra email để xác nhận tài khoản trước khi đăng nhập.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Đã đăng xuất thành công.');
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('login')->withErrors(['email' => 'Đường dẫn xác nhận không hợp lệ.']);
        }

        if (!is_null($user->email_verified_at)) {
            return redirect()->route('login')->with('success', 'Tài khoản của bạn đã được xác nhận trước đó. Vui lòng đăng nhập.');
        }

        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Xác nhận tài khoản thành công! Bạn đã được đăng nhập tự động.');
    }
}
