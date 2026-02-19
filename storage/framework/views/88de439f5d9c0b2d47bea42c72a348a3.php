<?php
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
    $firstImage = is_array($product->images) ? ($product->images[0] ?? '/images/placeholder.png') : '/images/placeholder.png';
?>
<a href="<?php echo e(route('products.show', $product->slug)); ?>" class="group block h-full">
    <div class="h-full flex flex-col">

        <!-- Image -->
        <div class="relative overflow-hidden rounded-lg bg-gray-100" style="aspect-ratio: 1/1;">
            <img src="<?php echo e($firstImage); ?>" alt="<?php echo e($product->name); ?>"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy" />

            <!-- Top-left type badge -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->product_type === 'clothing'): ?>
                <span class="absolute top-2.5 left-2.5 bg-purple-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">เสื้อผ้า</span>
            <?php elseif($product->product_type === 'other'): ?>
                <span class="absolute top-2.5 left-2.5 bg-gray-700 text-white text-[10px] font-bold px-2 py-0.5 rounded">อื่นๆ</span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Bottom-left badges -->
            <div class="absolute bottom-2.5 left-2.5 flex gap-1.5">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_featured): ?>
                    <span class="bg-red-600 text-white text-xs font-bold px-3 py-1 rounded">HOT</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->is_new): ?>
                    <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded">NEW</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($discountPercent > 0): ?>
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded">-<?php echo e($discountPercent); ?>%</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Wishlist -->
            <div class="absolute top-2.5 right-2.5 opacity-0 group-hover:opacity-100 transition-opacity duration-200" onclick="event.preventDefault(); event.stopPropagation();">
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('wishlist-button', ['product-id' => $product->id,'size' => 'sm']);

$key = 'wl-card-'.$product->id;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3001099045-0', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            </div>
        </div>

        <!-- Info -->
        <div class="pt-3 flex-1 flex flex-col gap-1.5">
            <h3 class="text-sm text-gray-800 line-clamp-2 leading-snug group-hover:text-[hsl(var(--primary))] transition-colors duration-200">
                <?php echo e($product->name); ?>

            </h3>
            <div class="mt-auto flex items-baseline gap-2">
                <span class="font-bold text-sm sm:text-base text-gray-900">฿ <?php echo e(number_format($product->price, 2)); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->original_price): ?>
                    <span class="text-xs text-gray-400 line-through">฿ <?php echo e(number_format($product->original_price, 2)); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

    </div>
</a>
<?php /**PATH C:\Project\pgmfshop\backend\resources\views/partials/product-card.blade.php ENDPATH**/ ?>