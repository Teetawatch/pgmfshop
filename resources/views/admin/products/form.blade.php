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
                                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                                    @elseif($typeKey === 'clothing')
                                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                    @else
                                        <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
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

                <div class="grid grid-cols-2 gap-4">
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

                <div class="grid grid-cols-2 gap-4">
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

                <div class="grid grid-cols-2 gap-4">
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
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                            ข้อมูลหนังสือ
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
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
                            <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
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
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/></svg>
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
                        <svg class="mx-auto h-10 w-10 text-gray-400 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.338-2.32 3.75 3.75 0 013.57 5.495A3.001 3.001 0 0118 19.5H6.75z"/>
                        </svg>
                        <p class="text-sm text-gray-500">คลิกหรือลากไฟล์มาวาง</p>
                        <p class="text-xs text-gray-400 mt-1">รองรับ JPG, PNG, GIF, WebP (สูงสุด 2MB ต่อไฟล์)</p>
                    </div>
                    <div id="preview-new" class="grid grid-cols-4 gap-3 mt-3"></div>
                </div>

                @if($product && !empty($product->images))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">รูปภาพปัจจุบัน</label>
                        <div class="grid grid-cols-4 gap-3" id="existing-images">
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

                <div class="flex items-center gap-6">
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
