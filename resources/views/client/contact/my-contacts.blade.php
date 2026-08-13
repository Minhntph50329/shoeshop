@extends('layouts.app')

@section('title', 'Tin nhắn của tôi - Veloce')

@section('content')
<div class="space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Tin nhắn của tôi</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800">Tin nhắn của tôi</h1>
            <p class="text-xs text-slate-500 mt-1">Xem lại các tin nhắn bạn đã gửi và phản hồi từ Veloce</p>
        </div>
        <a href="{{ route('contact') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-indigo-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Gửi tin nhắn mới
        </a>
    </div>

    @if($contacts->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm py-20 flex flex-col items-center gap-4 text-slate-400">
            <svg class="w-16 h-16 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="font-semibold">Bạn chưa gửi tin nhắn liên hệ nào</p>
            <a href="{{ route('contact') }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition">
                Gửi liên hệ ngay
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($contacts as $contact)
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden transition hover:shadow-md">
                {{-- Card Header --}}
                <div class="flex items-center justify-between px-6 py-4 bg-slate-50/60 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Gửi lúc {{ $contact->created_at->format('H:i, d/m/Y') }}</p>
                            @if($contact->phone)
                                <p class="text-[10px] text-slate-400">SĐT: {{ $contact->phone }}</p>
                            @endif
                        </div>
                    </div>
                    {{-- Status Badge --}}
                    @if($contact->status === 'replied')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            Đã được phản hồi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                            Đang chờ phản hồi
                        </span>
                    @endif
                </div>

                {{-- Message Body --}}
                <div class="p-6 space-y-4">
                    {{-- User Message --}}
                    <div class="flex gap-3 items-start">
                        <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-black text-sm shrink-0 mt-0.5">
                            {{ mb_strtoupper(mb_substr(auth()->user()->fullname ?? 'U', 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <p class="text-xs font-bold text-slate-700 mb-1">{{ auth()->user()->fullname ?? $contact->name }}</p>
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl rounded-tl-none p-4 text-xs text-slate-700 leading-relaxed">
                                {{ $contact->message }}
                            </div>
                        </div>
                    </div>

                    {{-- Admin Reply --}}
                    @if($contact->status === 'replied' && $contact->reply_message)
                        <div class="flex gap-3 items-start justify-end">
                            <div class="flex-1 flex flex-col items-end">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-xs font-bold text-indigo-700">Veloce Store</span>
                                    @if($contact->replied_at)
                                        <span class="text-[10px] text-slate-400">{{ $contact->replied_at->format('H:i, d/m/Y') }}</span>
                                    @endif
                                </div>
                                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl rounded-tr-none p-4 text-xs text-indigo-800 leading-relaxed max-w-[85%]">
                                    {{ $contact->reply_message }}
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                    @else
                        <div class="bg-amber-50/40 border border-dashed border-amber-200 rounded-xl p-4 text-center">
                            <p class="text-xs text-amber-600 font-medium">
                                ⏳ Chúng tôi đã nhận được tin nhắn và sẽ phản hồi bạn sớm nhất có thể!
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($contacts->hasPages())
            <div class="pt-2">
                {{ $contacts->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
