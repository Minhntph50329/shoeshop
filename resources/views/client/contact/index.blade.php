@extends('layouts.app')

@section('title', 'Liên hệ với Veloce')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-750 rounded-2xl text-xs flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-750 rounded-2xl text-xs space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 bg-white p-8 md:p-12 rounded-3xl border border-slate-100 shadow-sm">
        <div class="space-y-6">
            <h2 class="text-3xl font-black text-slate-800 leading-tight">Liên hệ với chúng tôi</h2>
            <p class="text-xs text-slate-500 leading-relaxed">Chúng tôi luôn sẵn sàng hỗ trợ bạn bất kì lúc nào. Hãy điền thông tin và gửi lời nhắn cho Veloce, đội ngũ CSKH của chúng tôi sẽ phản hồi bạn trong thời gian sớm nhất.</p>
            
            <div class="space-y-4 pt-4 border-t border-slate-100 text-xs text-slate-650">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <span>Hotline: <strong class="text-slate-800">1900 6789</strong></span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <span>Email: <strong class="text-slate-800">support@veloce.vn</strong></span>
                </div>
            </div>
        </div>
        
        <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Họ và tên <span class="text-rose-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->fullname ?? '') }}" required placeholder="Nguyễn Văn A" 
                    class="border border-slate-200 p-3 rounded-xl text-xs w-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Địa chỉ Email <span class="text-rose-500">*</span></label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required placeholder="example@email.com" 
                    class="border border-slate-200 p-3 rounded-xl text-xs w-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Số điện thoại</label>
                <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone_number ?? '') }}" placeholder="0901234567" 
                    class="border border-slate-200 p-3 rounded-xl text-xs w-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lời nhắn gửi <span class="text-rose-500">*</span></label>
                <textarea name="message" required minlength="10" placeholder="Nhập lời nhắn, ý kiến đóng góp hoặc câu hỏi của bạn gửi đến chúng tôi..." rows="4" 
                    class="border border-slate-200 p-3 rounded-xl text-xs w-full focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition resize-none">{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-xl text-xs transition shadow-md shadow-indigo-600/10">
                Gửi liên hệ
            </button>
        </form>
    </div>
</div>
@endsection
