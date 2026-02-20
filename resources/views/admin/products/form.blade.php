@extends('admin.layout')
@section('title', $product ? 'แก้ไขสินค้า' : 'เพิ่มสินค้า')

@section('content')
<div class="max-w-2xl" x-data="productForm()">
    <div class="bg-white rounded-xl border p-6">
        <form method="POST" action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf
            @if($product) @method('PUT') @endif

            @if($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <!-- Product Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ประเภทสินค้า *</label>
                    <div class="flex gap-2">
                        @foreach(\App\Models\Product::PRODUCT_TYPES as $typeKey => $typeLabel)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="product_type" value="{{ $typeKey }}" x-model="productType" class="hidden peer">
                                <div class="text-center py-2.5 px-3 border-2 rounded-lg text-sm font-medium transition-all
                                    peer-checked:border-teal-500 peer-checked:bg-teal-50 peer-checked:text-teal-700
                                    border-gray-200 text-gray-500 hover:border-gray-300">
                                    @if($typeKey === 'book')
                                        <x-heroicon-o-book-open class="w-5 h-5 mx-auto mb-1" />
                                    @elseif($typeKey === 'clothing')
                                        <x-heroicon-o-shopping-bag class="w-5 h-5 mx-auto mb-1" />
                                    @else
                                        <x-heroicon-o-archive-box class="w-5 h-5 mx-auto mb-1" />
                                    @endif
                                    {{ $typeLabel }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ชื่อสินค้า *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ *</label>
                        <select name="category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            <option value="">เลือกหมวดหมู่</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku ?? '') }}" placeholder="รหัสสินค้า (ถ้ามี)"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div x-show="productType !== 'clothing'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">สต็อก *</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" :required="productType !== 'clothing'"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div x-show="productType === 'clothing'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">สต็อกรวม</label>
                        <input type="text" value="{{ $product ? $product->stock : 0 }}" disabled
                            class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-gray-50 text-gray-500">
                        <p class="text-xs text-gray-400 mt-1">คำนวณจากสต็อกแต่ละตัวเลือกด้านล่าง</p>
                        <input type="hidden" name="stock" value="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">น้ำหนัก (กรัม)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight ?? '') }}" min="0" step="0.01" placeholder="เช่น 250"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ราคาขาย (฿) *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}" min="0" step="0.01" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">ราคาเดิม (฿)</label>
                        <input type="number" name="original_price" value="{{ old('original_price', $product->original_price ?? '') }}" min="0" step="0.01"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">รายละเอียด</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">{{ old('description', $product->description ?? '') }}</textarea>
                </div>

                <!-- Book-specific fields -->
                <template x-if="productType === 'book'">
                    <div class="space-y-4">
                        <hr class="border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <x-heroicon-o-book-open class="w-4 h-4 text-teal-600" />
                            ข้อมูลหนังสือ
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">สำนักพิมพ์</label>
                                <input type="text" name="publisher" value="{{ old('publisher', $product->publisher ?? '') }}" placeholder="เช่น สำนักพิมพ์ ABC"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">จำนวนหน้า</label>
                                <input type="number" name="pages" value="{{ old('pages', $product->pages ?? '') }}" min="0" placeholder="เช่น 320"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ผู้แต่ง (คั่นด้วย ,)</label>
                            <input type="text" name="authors" value="{{ old('authors', $product ? implode(', ', $product->authors ?? []) : '') }}" placeholder="เช่น สมชาย ใจดี, สมหญิง รักเรียน"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">หมวดหมู่ย่อย / Genres (คั่นด้วย ,)</label>
                            <input type="text" name="genres" value="{{ old('genres', $product ? implode(', ', $product->genres ?? []) : '') }}" placeholder="เช่น นิยาย, แฟนตาซี, ผจญภัย"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>
                    </div>
                </template>

                <!-- Clothing-specific fields -->
                <template x-if="productType === 'clothing'">
                    <div class="space-y-4">
                        <hr class="border-gray-200">
                        <h3 class="text-sm font-semibold text-gray-800 flex items-center gap-2">
                            <x-heroicon-o-shopping-bag class="w-4 h-4 text-teal-600" />
                            ข้อมูลเสื้อผ้า
                        </h3>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ไซส์ที่มี (คั่นด้วย ,)</label>
                            <input type="text" name="sizes" x-model="sizesInput" @input.debounce.500ms="buildVariantGrid()" value="{{ old('sizes', $product ? implode(', ', $product->sizes ?? []) : '') }}" placeholder="เช่น XS, S, M, L, XL, XXL"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                            <p class="text-xs text-gray-400 mt-1">ตัวอย่าง: XS, S, M, L, XL, XXL หรือ Free Size</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">สีที่มี (คั่นด้วย ,)</label>
                            <input type="text" name="colors" x-model="colorsInput" @input.debounce.500ms="buildVariantGrid()" value="{{ old('colors', $product ? implode(', ', $product->colors ?? []) : '') }}" placeholder="เช่น ดำ, ขาว, แดง, น้ำเงิน"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">วัสดุ / เนื้อผ้า</label>
                            <input type="text" name="material" value="{{ old('material', $product->material ?? '') }}" placeholder="เช่น Cotton 100%, Polyester, ผ้าฝ้าย"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                        </div>

                        <!-- Variant Stock Grid -->
                        <template x-if="variantGrid.length > 0">
                            <div>
                                <hr class="border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-800 flex items-center gap-2 mt-4 mb-3">
                                    <x-heroicon-o-archive-box class="w-4 h-4 text-purple-600" />
                                    สต็อกแต่ละตัวเลือก
                                    <span class="text-xs font-normal text-gray-400" x-text="'(' + variantGrid.length + ' ตัวเลือก)'"></span>
                                </h4>
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500" x-show="parsedSizes.length > 0">ไซส์</th>
                                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-500" x-show="parsedColors.length > 0">สี</th>
                                                <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 w-28">สต็อก</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <template x-for="(v, idx) in variantGrid" :key="v.key">
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-3 py-2 text-gray-700 font-medium" x-show="parsedSizes.length > 0" x-text="v.size || '-'"></td>
                                                    <td class="px-3 py-2 text-gray-700" x-show="parsedColors.length > 0" x-text="v.color || '-'"></td>
                                                    <td class="px-3 py-2 text-center">
                                                        <input type="number" :name="'variant_stock[' + v.key + ']'" x-model.number="v.stock" min="0"
                                                            class="w-20 px-2 py-1 border border-gray-300 rounded text-sm text-center focus:ring-2 focus:ring-teal-500 outline-none">
                                                    </td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <p class="text-xs text-gray-400">สต็อกรวม: <span class="font-semibold text-gray-600" x-text="variantGrid.reduce((sum, v) => sum + (parseInt(v.stock) || 0), 0)"></span> ชิ้น</p>
                                    <button type="button" @click="setAllStock()" class="text-xs text-teal-600 hover:text-teal-700 font-medium">ตั้งสต็อกทั้งหมด...</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                <hr class="border-gray-200">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">อัปโหลดรูปภาพ (เลือกได้หลายไฟล์)</label>
                    <div id="upload-area" class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-teal-400 transition-colors cursor-pointer">
                        <input type="file" name="upload_images[]" id="upload_images" multiple accept="image/*"
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <x-heroicon-o-cloud-arrow-up class="mx-auto h-10 w-10 text-gray-400 mb-2" />
                        <p class="text-sm text-gray-500">คลิกหรือลากไฟล์มาวาง</p>
                        <p class="text-xs text-gray-400 mt-1">รองรับ JPG, PNG, GIF, WebP (สูงสุด 2MB ต่อไฟล์)</p>
                    </div>
                    <div id="preview-new" class="grid grid-cols-3 sm:grid-cols-4 gap-3 mt-3"></div>
                </div>

                @if($product && !empty($product->images))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพปัจจุบัน</label>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-3" id="existing-images">
                            @foreach($product->images as $idx => $img)
                                <div class="relative group" id="existing-img-{{ $idx }}">
                                    <img src="{{ $img }}" alt="รูปที่ {{ $idx + 1 }}" class="w-full aspect-square object-cover rounded-lg border">
                                    <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                    <button type="button" onclick="removeExistingImage({{ $idx }})"
                                        class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow">
                                        ✕
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">แท็ก (คั่นด้วย ,)</label>
                    <input type="text" name="tags" value="{{ old('tags', $product ? implode(', ', $product->tags ?? []) : '') }}" placeholder="เช่น โปรโมชั่น, สินค้าใหม่"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
                </div>

                <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        สินค้าแนะนำ (HOT)
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        สินค้าใหม่ (NEW)
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        เปิดขาย
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-6 pt-4 border-t">
                <button type="submit" class="px-6 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium hover:bg-teal-700">
                    {{ $product ? 'บันทึกการแก้ไข' : 'เพิ่มสินค้า' }}
                </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg text-sm hover:bg-gray-200">ยกเลิก</a>
            </div>
        </form>
    </div>
</div>

<script>
    function productForm() {
        // Existing variant data from server
        const existingVariants = @json($product && $product->variants ? $product->variants->mapWithKeys(fn($v) => [($v->size ?: '') . '|' . ($v->color ?: '') => $v->stock]) : []);

        return {
            productType: '{{ old('product_type', $product->product_type ?? 'book') }}',
            sizesInput: '{{ old('sizes', $product ? implode(', ', $product->sizes ?? []) : '') }}',
            colorsInput: '{{ old('colors', $product ? implode(', ', $product->colors ?? []) : '') }}',
            variantGrid: [],
            parsedSizes: [],
            parsedColors: [],

            init() {
                this.buildVariantGrid();
            },

            buildVariantGrid() {
                this.parsedSizes = this.sizesInput.split(',').map(s => s.trim()).filter(s => s);
                this.parsedColors = this.colorsInput.split(',').map(s => s.trim()).filter(s => s);

                // Preserve current grid values
                const currentValues = {};
                this.variantGrid.forEach(v => { currentValues[v.key] = v.stock; });

                const grid = [];
                if (this.parsedSizes.length > 0 && this.parsedColors.length > 0) {
                    this.parsedSizes.forEach(size => {
                        this.parsedColors.forEach(color => {
                            const key = size + '|' + color;
                            grid.push({ size, color, key, stock: currentValues[key] ?? existingVariants[key] ?? 0 });
                        });
                    });
                } else if (this.parsedSizes.length > 0) {
                    this.parsedSizes.forEach(size => {
                        const key = size + '|';
                        grid.push({ size, color: null, key, stock: currentValues[key] ?? existingVariants[key] ?? 0 });
                    });
                } else if (this.parsedColors.length > 0) {
                    this.parsedColors.forEach(color => {
                        const key = '|' + color;
                        grid.push({ size: null, color, key, stock: currentValues[key] ?? existingVariants[key] ?? 0 });
                    });
                }
                this.variantGrid = grid;
            },

            setAllStock() {
                const val = prompt('ตั้งสต็อกทั้งหมดเป็น:', '10');
                if (val !== null && !isNaN(val)) {
                    this.variantGrid.forEach(v => { v.stock = parseInt(val); });
                }
            }
        };
    }

    // Preview new uploaded images
    document.getElementById('upload_images').addEventListener('change', function(e) {
        const preview = document.getElementById('preview-new');
        preview.innerHTML = '';
        Array.from(e.target.files).forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${ev.target.result}" class="w-full aspect-square object-cover rounded-lg border" alt="preview ${i+1}">
                    <span class="absolute bottom-1 left-1 bg-black/60 text-white text-[10px] px-1.5 py-0.5 rounded">ใหม่</span>
                `;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    // Remove existing image
    function removeExistingImage(idx) {
        const el = document.getElementById('existing-img-' + idx);
        if (el) {
            el.style.transition = 'opacity 0.3s, transform 0.3s';
            el.style.opacity = '0';
            el.style.transform = 'scale(0.8)';
            setTimeout(() => el.remove(), 300);
        }
    }
</script>
@endsection
