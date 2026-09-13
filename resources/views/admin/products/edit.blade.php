@extends('layouts.admin')

@section('title', 'Chỉnh sửa Sản phẩm (WP Style)')
@section('page_title', 'Chỉnh sửa Sản phẩm')

@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @method('PUT')

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Chỉnh sửa: {{ $product->name }}</h2>
            <p class="text-sm text-slate-500">Cập nhật thông tin chi tiết, hình ảnh và biến thể sản phẩm</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-sm font-semibold transition">
                Hủy bỏ
            </a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Cập nhật sản phẩm
            </button>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i data-lucide="alert-circle" class="w-4 h-4"></i> Vui lòng kiểm tra lại thông tin:
            </div>
            <ul class="list-disc list-inside pl-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- WordPress Style 2-Column Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- CỘT CHÍNH (BÊN TRÁI - 2 COLS) -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tên sản phẩm -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1">Tên sản phẩm <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 text-base border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-800">
                </div>
            </div>

            <!-- Loại sản phẩm -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-800 mb-1">Loại sản phẩm <span class="text-rose-500">*</span></label>
                    <select name="product_type" id="product_type" class="w-full px-4 py-3 text-base border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold text-slate-800">
                        <option value="simple" {{ old('product_type', $product->variants->count() > 0 ? 'variable' : 'simple') == 'simple' ? 'selected' : '' }}>Sản phẩm đơn giản</option>
                        <option value="variable" {{ old('product_type', $product->variants->count() > 0 ? 'variable' : 'simple') == 'variable' ? 'selected' : '' }}>Sản phẩm biến thể</option>
                    </select>
                </div>
            </div>

            <!-- Mô tả ngắn -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-2">
                <label class="block text-sm font-bold text-slate-800">Mô tả ngắn sản phẩm</label>
                <textarea name="short_description" rows="3" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('short_description', $product->short_description) }}</textarea>
            </div>

            <!-- Mô tả chi tiết -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-2">
                <label class="block text-sm font-bold text-slate-800">Mô tả chi tiết sản phẩm</label>
                <textarea name="description" rows="8" class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- Giá, Tồn kho & Giảm giá -->
            <div id="simple_product_fields" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-6">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i data-lucide="dollar-sign" class="w-5 h-5 text-indigo-600"></i> Dữ liệu sản phẩm & Giá bán
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Mã SKU (Để trống tự động sinh dạng PRD123456)</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" placeholder="VD: PRD123456 (Tự sinh nếu trống)" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Giá bán gốc (VNĐ) <span class="text-rose-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" step="1000" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Số lượng tồn kho <span class="text-rose-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <!-- Giảm giá & Thời gian -->
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-4">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-slate-600 flex items-center gap-1.5">
                        <i data-lucide="percent" class="w-4 h-4 text-emerald-600"></i> Chương trình Giảm giá / Khuyến mãi
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Số tiền giảm (VNĐ)</label>
                            <input type="number" name="discount" value="{{ old('discount', $product->discount) }}" min="0" step="1000" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="discount_start" value="{{ old('discount_start', $product->discount_start ? $product->discount_start->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-600 mb-1">Thời gian kết thúc</label>
                            <input type="datetime-local" name="discount_end" value="{{ old('discount_end', $product->discount_end ? $product->discount_end->format('Y-m-d\TH:i') : '') }}" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thư viện ảnh sản phẩm (Gallery) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i data-lucide="images" class="w-5 h-5 text-indigo-600"></i> Thư viện ảnh sản phẩm (Gallery)
                </h3>
                @if(!empty($product->gallery) && is_array($product->gallery))
                    <div class="grid grid-cols-4 sm:grid-cols-6 gap-3 mb-3">
                        @foreach($product->gallery as $imgUrl)
                            <img src="{{ asset($imgUrl) }}" alt="Gallery Img" class="w-full h-20 object-cover rounded-lg border border-slate-200 bg-slate-50">
                        @endforeach
                    </div>
                @endif
                <input type="file" name="gallery[]" id="gallery_input" multiple accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <div id="gallery_preview" class="grid grid-cols-4 sm:grid-cols-6 gap-3 pt-2"></div>
            </div>

            <!-- Thuộc tính thông tin (Không tạo biến thể) -->
            @if(isset($infoAttributes) && $infoAttributes->count() > 0)
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-600"></i> Thuộc tính thông tin (Không biến thể)
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Các thuộc tính này sẽ được hiển thị ở phần thông tin thêm trên trang chi tiết sản phẩm.</p>
                </div>
                @php
                    $selectedInfoValues = $product->infoAttributeValues->pluck('id')->toArray();
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($infoAttributes as $infoAttr)
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">{{ $infoAttr->name }}</label>
                            <select name="info_attribute_value_ids[]" multiple class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 h-24">
                                @foreach($infoAttr->values as $val)
                                    <option value="{{ $val->id }}" {{ in_array($val->id, $selectedInfoValues) ? 'selected' : '' }}>{{ $val->value }}</option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1">Giữ phím Ctrl/Cmd để chọn nhiều giá trị.</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Thuộc tính & Biến thể Sản phẩm -->
            <div id="variable_product_fields" class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                        <i data-lucide="layers" class="w-5 h-5 text-indigo-600"></i> Thuộc tính & Biến thể sản phẩm
                    </h3>
                    <button type="button" id="add_variant_btn" class="px-3 py-1.5 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 rounded-lg text-xs font-bold transition flex items-center gap-1">
                        <i data-lucide="plus" class="w-3.5 h-3.5"></i> Thêm biến thể
                    </button>
                </div>

                <div id="variants_wrapper" class="space-y-4">
                    @php $vIndex = 0; @endphp
                    @forelse($product->variants as $variant)
                        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-3 relative">
                            <input type="hidden" name="variants[{{ $vIndex }}][id]" value="{{ $variant->id }}">
                            <button type="button" onclick="this.closest('.relative').remove()" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 text-xs">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                @foreach($variantAttributes as $attr)
                                    @if($attr->values && $attr->values->count() > 0)
                                        @php
                                            $selectedValId = $variant->attributeValues->pluck('id')->toArray();
                                        @endphp
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-500">{{ $attr->name }}</label>
                                            <select name="variants[{{ $vIndex }}][attribute_value_ids][]" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                                                <option value="">-- Chọn {{ $attr->name }} --</option>
                                                @foreach($attr->values as $v)
                                                    <option value="{{ $v->id }}" {{ in_array($v->id, $selectedValId) ? 'selected' : '' }}>{{ $v->value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endforeach
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Mã SKU biến thể</label>
                                    <input type="text" name="variants[{{ $vIndex }}][sku]" value="{{ $variant->sku }}" placeholder="Để trống tự sinh PRD..." class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Giá riêng (VNĐ)</label>
                                    <input type="number" name="variants[{{ $vIndex }}][price]" value="{{ $variant->price }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500">Tồn kho biến thể</label>
                                    <input type="number" name="variants[{{ $vIndex }}][stock]" value="{{ $variant->stock }}" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Ảnh riêng biến thể</label>
                                    @if($variant->image)
                                        <div id="variant_image_preview_box_{{ $vIndex }}" class="mb-2 relative w-16 h-16">
                                            <img id="variant_image_preview_{{ $vIndex }}" src="{{ asset($variant->image) }}" class="w-full h-full object-cover rounded border border-slate-200">
                                            <button type="button" onclick="removeVariantImage({{ $vIndex }})" class="absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
                                                <i data-lucide="x" class="w-2 h-2"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div id="variant_image_preview_box_{{ $vIndex }}" class="mb-2 hidden relative w-16 h-16">
                                            <img id="variant_image_preview_{{ $vIndex }}" src="#" class="w-full h-full object-cover rounded border border-slate-200">
                                            <button type="button" onclick="removeVariantImage({{ $vIndex }})" class="absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
                                                <i data-lucide="x" class="w-2 h-2"></i>
                                            </button>
                                        </div>
                                    @endif
                                    <input type="file" name="variants[{{ $vIndex }}][image]" id="variant_image_input_{{ $vIndex }}" onchange="previewVariantImage(this, {{ $vIndex }})" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-1 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-indigo-50 file:text-indigo-700">
                                </div>
                            </div>
                        </div>
                        @php $vIndex++; @endphp
                    @empty
                        <p class="text-xs text-slate-400 font-italic text-center py-4" id="no_variants_msg">Chưa có biến thể nào. Nhấn "Thêm biến thể" để tạo.</p>
                    @endforelse
                </div>
            </div>

        </div>


        <!-- CỘT PHỤ (SIDEBAR BÊN PHẢI - 1 COL) -->
        <div class="space-y-6">

            <!-- Khối Đăng bài / Trạng thái -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="send" class="w-4 h-4 text-indigo-600"></i> Trạng thái xuất bản
                </h3>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Trạng thái sản phẩm</label>
                    <select name="status" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 font-semibold">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Hiển thị (Public)</option>
                        <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Bản nháp (Draft)</option>
                        <option value="hidden" {{ old('status', $product->status) == 'hidden' ? 'selected' : '' }}>Ẩn sản phẩm (Hidden)</option>
                    </select>
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-bold transition shadow-sm flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-4 h-4"></i> Cập nhật sản phẩm
                    </button>
                </div>
            </div>

            <!-- Ảnh đại diện chính (Featured Image) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-4">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="image" class="w-4 h-4 text-indigo-600"></i> Ảnh đại diện sản phẩm
                </h3>
                <div class="text-center">
                    @if($product->image)
                        <div class="mb-3">
                            <img src="{{ asset($product->image) }}" alt="Main Image" class="w-full h-48 object-contain rounded-lg border border-slate-200 bg-slate-50 p-2">
                        </div>
                    @endif
                    <div id="main_image_preview_box" class="mb-3 hidden relative">
                        <img id="main_image_preview" src="#" alt="Preview" class="w-full h-48 object-contain rounded-lg border border-slate-200 bg-slate-50 p-2">
                        <button type="button" id="remove_main_image_btn" class="absolute top-2 right-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-6 h-6 flex items-center justify-center shadow-sm">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    </div>
                    <input type="file" name="image" id="main_image_input" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
            </div>

            <!-- Khối Danh mục sản phẩm (Categories Select) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-3">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="folder" class="w-4 h-4 text-indigo-600"></i> Danh mục sản phẩm
                </h3>
                @php
                    $selectedCategoryIds = $product->categories->pluck('id')->toArray();
                @endphp
                <div class="space-y-2">
                    <select name="category_ids[]" multiple class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 h-32">
                        @forelse($categories as $cat)
                            <option value="{{ $cat->id }}" class="font-bold" {{ in_array($cat->id, $selectedCategoryIds) ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @if($cat->children->count() > 0)
                                @foreach($cat->children as $child)
                                    @if(isset($child->is_active) && !$child->is_active) @continue @endif
                                    <option value="{{ $child->id }}" {{ in_array($child->id, $selectedCategoryIds) ? 'selected' : '' }}>&nbsp;&nbsp;-- {{ $child->name }}</option>
                                @endforeach
                            @endif
                        @empty
                            <option value="" disabled>Chưa có danh mục nào.</option>
                        @endforelse
                    </select>
                    <p class="text-xs text-slate-500 mt-1">Giữ phím Ctrl (Windows) hoặc Command (Mac) để chọn nhiều danh mục.</p>
                </div>
            </div>

            <!-- Khối Thương hiệu (Brand Select) -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100 space-y-3">
                <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3 flex items-center gap-2">
                    <i data-lucide="tag" class="w-4 h-4 text-indigo-600"></i> Thương hiệu
                </h3>
                <select name="brand_id" class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">-- Chọn thương hiệu --</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>

        </div>
    </div>
</form>

<script>
    // Live Preview ảnh chính
    document.getElementById('main_image_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('main_image_preview').src = evt.target.result;
                document.getElementById('main_image_preview_box').classList.remove('hidden');
                lucide.createIcons();
            }
            reader.readAsDataURL(file);
        }
    });

    document.getElementById('remove_main_image_btn').addEventListener('click', function() {
        document.getElementById('main_image_input').value = '';
        document.getElementById('main_image_preview').src = '#';
        document.getElementById('main_image_preview_box').classList.add('hidden');
    });

    // Live Preview Thư viện ảnh
    let selectedGalleryFiles = [];
    document.getElementById('gallery_input').addEventListener('change', function(e) {
        const preview = document.getElementById('gallery_preview');
        
        // Add new files to the array
        const newFiles = Array.from(e.target.files);
        selectedGalleryFiles = selectedGalleryFiles.concat(newFiles);
        
        updateGalleryInputAndPreview();
    });

    function updateGalleryInputAndPreview() {
        const preview = document.getElementById('gallery_preview');
        const input = document.getElementById('gallery_input');
        
        // Update input files using DataTransfer
        const dt = new DataTransfer();
        selectedGalleryFiles.forEach(file => dt.items.add(file));
        input.files = dt.files;
        
        // Render preview
        preview.innerHTML = '';
            }
            reader.readAsDataURL(file);
        });
    });

    // Dynamic Variant addition
    let variantIndex = {{ isset($vIndex) ? $vIndex : 0 }};
    const attributesData = @json($variantAttributes);

    document.getElementById('add_variant_btn').addEventListener('click', function() {
        const msg = document.getElementById('no_variants_msg');
        if (msg) msg.classList.add('hidden');
        const container = document.getElementById('variants_wrapper');
        
        let attrSelectsHtml = '';
        attributesData.forEach(attr => {
            if (attr.values && attr.values.length > 0) {
                attrSelectsHtml += `
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500">${attr.name}</label>
                        <select name="variants[${variantIndex}][attribute_value_ids][]" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                            <option value="">-- Chọn ${attr.name} --</option>
                            ${attr.values.map(v => `<option value="${v.id}">${v.value}</option>`).join('')}
                        </select>
                    </div>
                `;
            }
        });

        const card = document.createElement('div');
        card.className = 'p-4 bg-slate-50 rounded-lg border border-slate-200 space-y-3 relative';
        card.innerHTML = `
            <button type="button" onclick="this.closest('.relative').remove()" class="absolute top-2 right-2 text-slate-400 hover:text-rose-500 text-xs">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                ${attrSelectsHtml}
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500">Mã SKU biến thể</label>
                    <input type="text" name="variants[${variantIndex}][sku]" placeholder="SKU Riêng" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500">Giá riêng (VNĐ)</label>
                    <input type="number" name="variants[${variantIndex}][price]" placeholder="Để trống nếu bằng giá gốc" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500">Tồn kho biến thể</label>
                    <input type="number" name="variants[${variantIndex}][stock]" value="10" class="w-full px-2.5 py-1.5 border border-slate-200 rounded text-xs">
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-slate-500 mb-1">Ảnh riêng biến thể</label>
                    <div id="variant_image_preview_box_${variantIndex}" class="mb-2 hidden relative w-16 h-16">
                        <img id="variant_image_preview_${variantIndex}" src="#" alt="Preview" class="w-full h-full object-cover rounded border border-slate-200">
                        <button type="button" onclick="removeVariantImage(${variantIndex})" class="absolute -top-1 -right-1 bg-rose-500 hover:bg-rose-600 text-white rounded-full w-4 h-4 flex items-center justify-center shadow-sm">
                            <i data-lucide="x" class="w-2 h-2"></i>
                        </button>
                    </div>
                    <input type="file" name="variants[${variantIndex}][image]" id="variant_image_input_${variantIndex}" onchange="previewVariantImage(this, ${variantIndex})" accept="image/*" class="w-full text-[11px] text-slate-500 file:mr-1 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:bg-indigo-50 file:text-indigo-700">
                </div>
            </div>
        `;
        container.appendChild(card);
        variantIndex++;
        lucide.createIcons();
    });

    function previewVariantImage(input, index) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('variant_image_preview_' + index).src = e.target.result;
                document.getElementById('variant_image_preview_box_' + index).classList.remove('hidden');
                lucide.createIcons();
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeVariantImage(index) {
        document.getElementById('variant_image_input_' + index).value = '';
        document.getElementById('variant_image_preview_' + index).src = '#';
        document.getElementById('variant_image_preview_box_' + index).classList.add('hidden');
    }

    // Toggle Product Type Fields
    const productTypeSelect = document.getElementById('product_type');
    const simpleFields = document.getElementById('simple_product_fields');
    const variableFields = document.getElementById('variable_product_fields');

    function toggleProductType() {
        const type = productTypeSelect.value;
        if (type === 'simple') {
            simpleFields.classList.remove('hidden');
            variableFields.classList.add('hidden');
            
            // Re-enable validation attributes on simple product inputs
            simpleFields.querySelectorAll('input[required_on_simple]').forEach(input => {
                input.setAttribute('required', 'required');
            });
        } else {
            simpleFields.classList.add('hidden');
            variableFields.classList.remove('hidden');
            
            // Remove required attribute from simple fields when hidden
            simpleFields.querySelectorAll('input[required]').forEach(input => {
                input.removeAttribute('required');
                input.setAttribute('required_on_simple', 'true');
            });

            // If no variants exist, click the add variant button once
            const container = document.getElementById('variants_wrapper');
            if (container.children.length === 0 || (container.children.length === 1 && container.children[0].id === 'no_variants_msg')) {
                document.getElementById('add_variant_btn').click();
            }
        }
    }

    productTypeSelect.addEventListener('change', toggleProductType);
    toggleProductType(); // Run on load
</script>
@endsection
