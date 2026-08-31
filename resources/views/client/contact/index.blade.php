@extends('layouts.app')

@section('title', 'Liên hệ với Veloce')

@section('content')

<div class="max-w-6xl mx-auto space-y-6 sm:space-y-8">

    {{-- ================= ALERTS ================= --}}

    @if(session('success'))

        <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-xs text-emerald-700 shadow-sm">

            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">

                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 13l4 4L19 7"/>
                </svg>

            </div>

            <div class="pt-1">
                {{ session('success') }}
            </div>

        </div>

    @endif


    @if($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-xs text-rose-700 shadow-sm">

            <div class="mb-2 flex items-center gap-2 font-bold">

                <div class="flex h-7 w-7 items-center justify-center rounded-full bg-rose-100">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>

                </div>

                Vui lòng kiểm tra lại thông tin

            </div>


            <div class="space-y-1 pl-9">

                @foreach($errors->all() as $error)

                    <p class="flex items-center gap-2">

                        <span class="h-1.5 w-1.5 rounded-full bg-rose-400"></span>

                        {{ $error }}

                    </p>

                @endforeach

            </div>

        </div>

    @endif



    {{-- ================= HEADER ================= --}}

    <div class="border-b border-slate-200 pb-5">

        <div class="flex items-center gap-3">

            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>

            </div>


            <div>

                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">
                    Veloce Support
                </p>

                <h1 class="text-xl font-black text-slate-900 sm:text-2xl">
                    Liên hệ với chúng tôi
                </h1>

            </div>

        </div>

    </div>



    {{-- ================= CONTACT SECTION ================= --}}

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 lg:gap-8">


        {{-- ========== LEFT: INFORMATION ========== --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

            <div>

                <h2 class="text-xl font-black leading-tight text-slate-800 sm:text-2xl">
                    Chúng tôi luôn sẵn sàng hỗ trợ bạn
                </h2>

                <p class="mt-3 text-xs leading-relaxed text-slate-500 sm:text-sm">

                    Hãy điền thông tin và gửi lời nhắn cho Veloce.
                    Đội ngũ chăm sóc khách hàng của chúng tôi sẽ phản hồi
                    trong thời gian sớm nhất.

                </p>

            </div>



            {{-- Contact information --}}

            <div class="mt-6 space-y-4 border-t border-slate-100 pt-6">


                {{-- Hotline --}}

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>

                    </div>


                    <div>

                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Hotline
                        </p>

                        <p class="mt-0.5 text-sm font-bold text-slate-800">
                            1900 6789
                        </p>

                    </div>

                </div>



                {{-- Email --}}

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>

                    </div>


                    <div class="min-w-0">

                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Email
                        </p>

                        <p class="mt-0.5 truncate text-sm font-bold text-slate-800">
                            support@veloce.vn
                        </p>

                    </div>

                </div>



                {{-- Address --}}

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-500">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>

                    </div>


                    <div>

                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                            Khu vực hỗ trợ
                        </p>

                        <p class="mt-0.5 text-sm font-bold text-slate-800">
                            Chương Mỹ, Hà Nội
                        </p>

                    </div>

                </div>


            </div>



            {{-- MAP --}}

            <div class="mt-7 overflow-hidden rounded-2xl border border-slate-200">

                <div class="flex items-center justify-between border-b border-slate-100 bg-white px-4 py-3">

                    <div class="flex items-center gap-2">

                        <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-rose-50 text-rose-500">

                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/>
                            </svg>

                        </div>

                        <span class="text-xs font-bold text-slate-700">
                            Vị trí của chúng tôi
                        </span>

                    </div>

                    <span class="text-[10px] text-slate-400">
                        Chương Mỹ, Hà Nội
                    </span>

                </div>


                <div class="h-64 w-full sm:h-72">

                    <iframe
                        src="https://www.google.com/maps?q=Ch%C6%B0%C6%A1ng%20M%E1%BB%B9,%20H%C3%A0%20N%E1%BB%99i&output=embed"
                        class="h-full w-full border-0"
                        loading="lazy"
                        allowfullscreen
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>

                </div>

            </div>

        </div>



        {{-- ========== RIGHT: CONTACT FORM ========== --}}

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

            <div class="mb-6">

                <div class="flex items-center gap-2">

                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">

                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4v16m8-8H4"/>
                        </svg>

                    </span>

                    <h2 class="text-lg font-black text-slate-800">
                        Gửi lời nhắn
                    </h2>

                </div>


                <p class="mt-2 text-xs leading-relaxed text-slate-500">
                    Điền thông tin bên dưới để gửi yêu cầu hỗ trợ đến Veloce.
                </p>

            </div>



            <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">

                @csrf


                {{-- Name --}}

                <div>

                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                        Họ và tên

                        <span class="text-rose-500">*</span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', auth()->user()->fullname ?? '') }}"
                        required
                        placeholder="Nguyễn Văn A"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                </div>



                {{-- Email --}}

                <div>

                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                        Địa chỉ Email

                        <span class="text-rose-500">*</span>

                    </label>


                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', auth()->user()->email ?? '') }}"
                        required
                        placeholder="example@email.com"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                </div>



                {{-- Phone --}}

                <div>

                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">
                        Số điện thoại
                    </label>


                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone', auth()->user()->phone_number ?? '') }}"
                        placeholder="0901234567"
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">

                </div>



                {{-- Message --}}

                <div>

                    <label class="mb-1.5 block text-xs font-semibold text-slate-700">

                        Lời nhắn gửi

                        <span class="text-rose-500">*</span>

                    </label>


                    <textarea
                        name="message"
                        required
                        minlength="10"
                        rows="5"
                        placeholder="Nhập lời nhắn, ý kiến đóng góp hoặc câu hỏi của bạn gửi đến chúng tôi..."
                        class="w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-3 text-xs leading-relaxed text-slate-700 transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-indigo-500/10">{{ old('message') }}</textarea>

                </div>



                {{-- Submit --}}

                <button
                    type="submit"
                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 py-3 text-xs font-bold text-white shadow-md shadow-indigo-600/20 transition duration-200 hover:bg-indigo-700 hover:shadow-lg active:scale-[0.99]">

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>

                    Gửi liên hệ

                </button>


            </form>

        </div>

    </div>

</div>

@endsection