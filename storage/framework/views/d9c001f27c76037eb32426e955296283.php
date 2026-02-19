<?php $__env->startSection('title', 'จัดการสต็อกสินค้า'); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">สินค้าทั้งหมด</p>
        <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo e(number_format($stats['total'])); ?></p>
    </div>
    <a href="?status=out" class="bg-white rounded-xl p-4 border hover:border-red-300 transition <?php echo e(request('status') === 'out' ? 'ring-2 ring-red-400' : ''); ?>">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">หมดสต็อก</p>
        <p class="text-2xl font-bold text-red-600 mt-1"><?php echo e(number_format($stats['out_of_stock'])); ?></p>
    </a>
    <a href="?status=low" class="bg-white rounded-xl p-4 border hover:border-orange-300 transition <?php echo e(request('status') === 'low' ? 'ring-2 ring-orange-400' : ''); ?>">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">วิกฤต (≤10)</p>
        <p class="text-2xl font-bold text-orange-600 mt-1"><?php echo e(number_format($stats['low_stock'])); ?></p>
    </a>
    <a href="?status=warning" class="bg-white rounded-xl p-4 border hover:border-yellow-300 transition <?php echo e(request('status') === 'warning' ? 'ring-2 ring-yellow-400' : ''); ?>">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">เตือน (≤30)</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1"><?php echo e(number_format($stats['warning_stock'])); ?></p>
    </a>
    <div class="bg-white rounded-xl p-4 border">
        <p class="text-[10px] text-gray-500 uppercase tracking-wide">มูลค่าสต็อก</p>
        <p class="text-xl font-bold text-teal-700 mt-1">฿<?php echo e(number_format($stats['total_value'], 0)); ?></p>
    </div>
</div>

<!-- Toolbar -->
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <form method="GET" class="flex flex-wrap items-center gap-2">
        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="ค้นหาสินค้า..."
            class="px-3 py-2 border border-gray-300 rounded-lg text-sm w-56 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
        <select name="category" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกหมวดหมู่</option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <option value="<?php echo e($cat->id); ?>" <?php echo e(request('category') == $cat->id ? 'selected' : ''); ?>><?php echo e($cat->name); ?></option>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </select>
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500 outline-none">
            <option value="">ทุกสถานะ</option>
            <option value="out" <?php echo e(request('status') === 'out' ? 'selected' : ''); ?>>หมดสต็อก</option>
            <option value="low" <?php echo e(request('status') === 'low' ? 'selected' : ''); ?>>วิกฤต (≤10)</option>
            <option value="warning" <?php echo e(request('status') === 'warning' ? 'selected' : ''); ?>>เตือน (≤30)</option>
            <option value="ok" <?php echo e(request('status') === 'ok' ? 'selected' : ''); ?>>ปกติ (>30)</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm hover:bg-gray-700">ค้นหา</button>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->hasAny(['search', 'category', 'status'])): ?>
            <a href="<?php echo e(route('admin.stock.index')); ?>" class="px-3 py-2 text-gray-500 text-sm hover:text-gray-700">✕ ล้าง</a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </form>
    <div class="flex items-center gap-2">
        <a href="<?php echo e(route('admin.stock.history')); ?>" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
             ประวัติทั้งหมด
        </a>
        <a href="<?php echo e(route('admin.stock.bulk')); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
             ปรับสต็อกหลายรายการ
        </a>
        <a href="<?php echo e(route('admin.stock.export')); ?>" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700">
             Export CSV
        </a>
    </div>
</div>

<!-- Products Table -->
<div class="bg-white rounded-xl border overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-xs text-gray-500">
            <tr>
                <th class="px-4 py-3 text-left">สินค้า</th>
                <th class="px-4 py-3 text-left">หมวดหมู่</th>
                <th class="px-4 py-3 text-right">ราคา</th>
                <th class="px-4 py-3 text-center">
                    <a href="?<?php echo e(http_build_query(array_merge(request()->except(['sort','dir']), ['sort' => 'stock', 'dir' => request('sort') === 'stock' && request('dir') === 'asc' ? 'desc' : 'asc']))); ?>"
                       class="hover:text-teal-600">
                        สต็อก <?php echo request('sort','stock') === 'stock' ? (request('dir') === 'desc' ? '↓' : '↑') : ''; ?>

                    </a>
                </th>
                <th class="px-4 py-3 text-center">
                    <a href="?<?php echo e(http_build_query(array_merge(request()->except(['sort','dir']), ['sort' => 'sold', 'dir' => request('sort') === 'sold' && request('dir') === 'desc' ? 'asc' : 'desc']))); ?>"
                       class="hover:text-teal-600">
                        ขายแล้ว <?php echo request('sort') === 'sold' ? (request('dir') === 'desc' ? '↓' : '↑') : ''; ?>

                    </a>
                </th>
                <th class="px-4 py-3 text-center">สถานะ</th>
                <th class="px-4 py-3 text-center">ปรับสต็อก</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
            <tr class="hover:bg-gray-50 <?php echo e($product->stock === 0 ? 'bg-red-50/50' : ($product->stock <= 10 ? 'bg-orange-50/50' : '')); ?>" id="row-<?php echo e($product->id); ?>">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->images && count($product->images) > 0): ?>
                            <img src="<?php echo e($product->images[0]); ?>" alt="" class="w-9 h-9 rounded-lg object-cover bg-gray-100">
                        <?php else: ?>
                            <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-[10px]">N/A</div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="min-w-0">
                            <a href="<?php echo e(route('admin.stock.show', $product)); ?>" class="font-medium text-gray-800 hover:text-teal-600 truncate block max-w-[200px]"><?php echo e($product->name); ?></a>
                            <p class="text-[10px] text-gray-400">ID: <?php echo e($product->id); ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600 text-xs"><?php echo e($product->category->name ?? '-'); ?></td>
                <td class="px-4 py-3 text-right font-medium">฿<?php echo e(number_format($product->price, 0)); ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="text-lg font-bold <?php echo e($product->stock === 0 ? 'text-red-600' : ($product->stock <= 10 ? 'text-orange-600' : ($product->stock <= 30 ? 'text-yellow-600' : 'text-gray-800'))); ?>">
                        <?php echo e(number_format($product->stock)); ?>

                    </span>
                </td>
                <td class="px-4 py-3 text-center text-gray-600"><?php echo e(number_format($product->sold)); ?></td>
                <td class="px-4 py-3 text-center">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->stock === 0): ?>
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-red-100 text-red-700">หมด</span>
                    <?php elseif($product->stock <= 10): ?>
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700">วิกฤต</span>
                    <?php elseif($product->stock <= 30): ?>
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-100 text-yellow-700">เตือน</span>
                    <?php else: ?>
                        <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">ปกติ</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </td>
                <td class="px-4 py-2 text-center">
                    <form action="<?php echo e(route('admin.stock.adjust', $product)); ?>" method="POST" class="flex items-center gap-1 justify-center">
                        <?php echo csrf_field(); ?>
                        <select name="type" class="px-1.5 py-1 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-teal-400 outline-none">
                            <option value="in">+ เข้า</option>
                            <option value="out">- ออก</option>
                            <option value="adjust">= ตั้ง</option>
                        </select>
                        <input type="number" name="quantity" min="0" placeholder="จำนวน"
                            class="w-16 px-2 py-1 border border-gray-200 rounded text-xs text-center focus:ring-1 focus:ring-teal-400 outline-none">
                        <input type="text" name="reason" placeholder="เหตุผล" required
                            class="w-24 px-2 py-1 border border-gray-200 rounded text-xs focus:ring-1 focus:ring-teal-400 outline-none">
                        <button type="submit" class="px-2 py-1 bg-teal-600 text-white rounded text-xs hover:bg-teal-700 whitespace-nowrap">บันทึก</button>
                    </form>
                </td>
            </tr>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">ไม่พบสินค้า</td></tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4"><?php echo e($products->withQueryString()->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Project\pgmfshop\backend\resources\views/admin/stock/index.blade.php ENDPATH**/ ?>