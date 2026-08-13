@extends('layouts.app')

@section('title', 'Thêm địa chỉ mới - Veloce')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-slate-500">
        <a href="{{ route('home') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span class="text-slate-300">/</span>
        <a href="{{ route('checkout') }}" class="hover:text-indigo-600 transition">Thanh toán</a>
        <span class="text-slate-300">/</span>
        <span class="text-slate-800 font-semibold">Thêm địa chỉ mới</span>
    </nav>

    {{-- Alerts --}}
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-1.5"><i data-lucide="x-circle" class="w-4 h-4"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-100/50 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-black text-lg text-slate-800">Thêm địa chỉ giao hàng mới</h3>
                <p class="text-xs text-slate-400 mt-1">Vui lòng chọn Tỉnh/Thành, Quận/Huyện, Phường/Xã từ danh sách gợi ý</p>
            </div>
            <a href="{{ route('checkout') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 transition flex items-center gap-1">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Quay lại
            </a>
        </div>

        <form action="{{ route('checkout.address.store') }}" method="POST" class="p-8 space-y-6" id="addressForm">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Fullname --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Họ tên người nhận <span class="text-rose-500">*</span></label>
                    <input type="text" name="fullname" value="{{ old('fullname', auth()->user()->fullname) }}" required placeholder="Nguyễn Văn A" 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>

                {{-- Phone Number --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">SĐT người nhận <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', auth()->user()->phone_number) }}" required placeholder="0912345678" 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Province Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tỉnh / Thành phố <span class="text-rose-500">*</span></label>
                    <input type="text" id="provinceInput" name="province" required autocomplete="off" placeholder="Gõ để tìm kiếm tỉnh..." 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition">
                    <div id="provinceList" class="absolute left-0 w-full max-h-60 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>

                {{-- District Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Quận / Huyện <span class="text-rose-500">*</span></label>
                    <input type="text" id="districtInput" name="district" required disabled autocomplete="off" placeholder="Chọn tỉnh trước..." 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
                    <div id="districtList" class="absolute left-0 w-full max-h-60 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>

                {{-- Ward Input --}}
                <div class="relative">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Phường / Xã <span class="text-rose-500">*</span></label>
                    <input type="text" id="wardInput" name="ward" required disabled autocomplete="off" placeholder="Chọn huyện trước..." 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
                    <div id="wardList" class="absolute left-0 w-full max-h-60 overflow-y-auto bg-white border border-slate-200 rounded-xl shadow-lg z-50 mt-1 hidden divide-y divide-slate-50"></div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Street (Số nhà, tên đường...) --}}
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tên đường, Tòa nhà, Số nhà <span class="text-rose-500">*</span></label>
                    <input type="text" id="streetInput" name="street" required disabled placeholder="Nhập số nhà, tên đường cụ thể..." 
                        class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition disabled:bg-slate-50 disabled:cursor-not-allowed">
                </div>

                {{-- Address Type --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Loại địa chỉ</label>
                    <select name="address_type" class="w-full py-3 px-4 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 transition bg-white">
                        <option value="home">Nhà riêng</option>
                        <option value="office">Văn phòng</option>
                        <option value="other">Khác</option>
                    </select>
                </div>
            </div>

            {{-- Compiled Address (Gửi đi chi tiết địa chỉ đầy đủ) --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Địa chỉ hiển thị đầy đủ</label>
                <input type="text" id="compiledAddressInput" name="address" required readonly placeholder="Địa chỉ sẽ tự động tạo..." 
                    class="w-full py-3 px-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-500 cursor-not-allowed">
            </div>

            {{-- Default Address Choice --}}
            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="is_default" value="1" checked class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500">
                    <span class="text-xs font-bold text-slate-700">Đặt làm địa chỉ mặc định cho các lần mua tiếp theo</span>
                </label>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-4 gap-3">
                <a href="{{ route('checkout') }}" class="px-6 py-3.5 border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-xs uppercase tracking-wider transition">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-200 transition">
                    Thêm địa chỉ mới
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const provinceInput = document.getElementById('provinceInput');
    const provinceList = document.getElementById('provinceList');
    const districtInput = document.getElementById('districtInput');
    const districtList = document.getElementById('districtList');
    const wardInput = document.getElementById('wardInput');
    const wardList = document.getElementById('wardList');
    const streetInput = document.getElementById('streetInput');
    const compiledInput = document.getElementById('compiledAddressInput');

    let allProvinces = [];
    let districts = [];
    let wards = [];

    let selectedProvinceCode = null;
    let selectedDistrictCode = null;

    // Fetch Provinces on Load
    fetch('https://provinces.open-api.vn/api/?depth=1')
        .then(res => res.json())
        .then(data => {
            allProvinces = data;
        })
        .catch(err => {
            console.error('Không thể tải dữ liệu Tỉnh/Thành phố:', err);
        });

    // Custom Autocomplete Helper
    function renderSuggestions(inputElement, listElement, getDataList, onSelect) {
        // Show dropdown on click
        inputElement.addEventListener('focus', () => {
            filterAndShow();
        });

        // Hide dropdown on click outside
        document.addEventListener('click', (e) => {
            if (!inputElement.contains(e.target) && !listElement.contains(e.target)) {
                listElement.classList.add('hidden');
            }
        });

        // Filter suggestions on typing
        inputElement.addEventListener('input', () => {
            filterAndShow();
        });

        function filterAndShow() {
            const dataList = getDataList();
            const query = inputElement.value.trim().toLowerCase();
            const filtered = dataList.filter(item => item.name.toLowerCase().includes(query));

            if (filtered.length > 0) {
                listElement.innerHTML = filtered.map(item => `
                    <div class="px-4 py-2.5 text-xs text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 cursor-pointer font-medium transition" data-code="${item.code}">
                        ${item.name}
                    </div>
                `).join('');
                listElement.classList.remove('hidden');

                // Add click handlers
                listElement.querySelectorAll('[data-code]').forEach(el => {
                    el.addEventListener('click', () => {
                        const code = el.getAttribute('data-code');
                        const name = el.innerText.trim();
                        inputElement.value = name;
                        listElement.classList.add('hidden');
                        onSelect(code, name);
                    });
                });
            } else {
                listElement.innerHTML = `<div class="px-4 py-2.5 text-xs text-slate-400">Không tìm thấy kết quả</div>`;
                listElement.classList.remove('hidden');
            }
        }
    }

    // Initialize Provinces
    renderSuggestions(provinceInput, provinceList, () => allProvinces, (code, name) => {
        selectedProvinceCode = code;
        
        // Reset and lock subsequent inputs
        districtInput.value = '';
        districtInput.disabled = true;
        districtInput.placeholder = 'Đang tải danh sách quận/huyện...';
        districtList.classList.add('hidden');

        wardInput.value = '';
        wardInput.disabled = true;
        wardInput.placeholder = 'Chọn huyện trước...';
        wardList.classList.add('hidden');

        streetInput.value = '';
        streetInput.disabled = true;

        updateCompiledAddress();

        // Fetch districts
        fetch(`https://provinces.open-api.vn/api/p/${code}?depth=2`)
            .then(res => res.json())
            .then(data => {
                districts = data.districts || [];
                districtInput.disabled = false;
                districtInput.placeholder = 'Gõ để tìm quận/huyện...';
                
                // Set up district autocomplete
                setupDistrictSuggestions();
            })
            .catch(err => {
                console.error('Lỗi tải danh sách Quận/Huyện:', err);
                districtInput.placeholder = 'Không thể tải quận/huyện';
            });
    });

    function setupDistrictSuggestions() {
        renderSuggestions(districtInput, districtList, () => districts, (code, name) => {
            selectedDistrictCode = code;

            // Reset and lock ward input
            wardInput.value = '';
            wardInput.disabled = true;
            wardInput.placeholder = 'Đang tải danh sách phường/xã...';
            wardList.classList.add('hidden');

            streetInput.value = '';
            streetInput.disabled = true;

            updateCompiledAddress();

            // Fetch Wards
            fetch(`https://provinces.open-api.vn/api/d/${code}?depth=2`)
                .then(res => res.json())
                .then(data => {
                    wards = data.wards || [];
                    wardInput.disabled = false;
                    wardInput.placeholder = 'Gõ để tìm phường/xã...';

                    // Set up ward autocomplete
                    setupWardSuggestions();
                })
                .catch(err => {
                    console.error('Lỗi tải danh sách Phường/Xã:', err);
                    wardInput.placeholder = 'Không thể tải phường/xã';
                });
        });
    }

    function setupWardSuggestions() {
        renderSuggestions(wardInput, wardList, () => wards, (code, name) => {
            streetInput.disabled = false;
            streetInput.placeholder = 'Nhập số nhà, ngõ ngách, tên đường...';
            
            updateCompiledAddress();
        });
    }

    // Update compiled address when street is entered
    streetInput.addEventListener('input', updateCompiledAddress);

    function updateCompiledAddress() {
        const p = provinceInput.value.trim();
        const d = districtInput.value.trim();
        const w = wardInput.value.trim();
        const s = streetInput.value.trim();

        const parts = [];
        if (s) parts.push(s);
        if (w) parts.push(w);
        if (d) parts.push(d);
        if (p) parts.push(p);

        compiledInput.value = parts.join(', ');
    }
});
</script>
@endsection
