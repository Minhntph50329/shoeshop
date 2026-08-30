<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\Client\Auth\LoginRequest;
use App\Http\Requests\Client\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Mail\LoginNotification;
use App\Mail\VerifyRegistration;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\Client\Auth\ForgotPasswordRequest;
use App\Http\Requests\Client\Auth\ResetPasswordRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('client.authentication.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

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

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

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

        // Send admin notification
        app(\App\Services\AdminNotificationService::class)->sendNewCustomer($user);

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
    public function showForgotPasswordForm()
    {
        return view('client.authentication.forgot-password');
    }

    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $email = $request->validated()['email'];
        
        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        try {
            Mail::to($email)->send(new ResetPasswordMail($resetUrl));
            return back()->with('success', 'Chúng tôi đã gửi một email chứa liên kết đặt lại mật khẩu cho bạn.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi email quên mật khẩu: ' . $e->getMessage());
            return back()->withInput()->withErrors(['email' => 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau.']);
        }
    }

    public function showResetPasswordForm(Request $request, $token)
    {
        return view('client.authentication.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $email = $validated['email'];

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || !Hash::check($validated['token'], $record->token)) {
            return back()->withInput()->withErrors(['email' => 'Liên kết khôi phục không hợp lệ hoặc đã hết hạn.']);
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withInput()->withErrors(['email' => 'Tài khoản không tồn tại.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $email)->delete();

        return redirect()->route('login')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập bằng mật khẩu mới.');
    }
}
