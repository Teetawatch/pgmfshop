<?php
    $images = is_array($product->images) ? $product->images : [];
    $discountPercent = $product->original_price ? round(($product->original_price - $product->price) / $product->original_price * 100) : 0;
?>

<div class="container mx-auto px-4 py-8">
    <!-- Enhanced Breadcrumb -->
    <div class="mb-8">
        <nav class="flex items-center space-x-2 text-sm" aria-label="Breadcrumb">
            <a href="<?php echo e(route('home')); ?>" class="flex items-center text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                หน้าแรก
            </a>
            
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            
            <a href="<?php echo e(route('products')); ?>" class="text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                สินค้า
            </a>
            
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            
            <a href="<?php echo e(route('products', ['category' => $product->category->slug])); ?>" class="text-gray-500 hover:text-[hsl(var(--primary))] transition-colors duration-200">
                <?php echo e($product->category->name); ?>

            </a>
            
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            
            <span class="text-gray-900 font-medium"><?php echo e($product->name); ?></span>
        </nav>
    </div>

    <!-- JSON-LD Product Structured Data -->
    <?php
        $jsonLd = [
            "\x40context" => 'https://schema.org',
            "\x40type" => 'Product',
            'name' => $product->name,
            'description' => mb_substr(strip_tags($product->description ?? ''), 0, 300),
            'image' => !empty($images) ? $images : [],
            'sku' => $product->sku ?: (string) $product->id,
            'category' => $product->product_type_label,
            'brand' => [
                "\x40type" => 'Organization',
                'name' => 'PGMF Shop',
            ],
            'offers' => [
                "\x40type" => 'Offer',
                'url' => url()->current(),
                'priceCurrency' => 'THB',
                'price' => number_format((float) $product->price, 2, '.', ''),
                'availability' => $product->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller' => [
                    "\x40type" => 'Organization',
                    'name' => 'PGMF Shop',
                ],
            ],
        ];
        if ($product->review_count > 0) {
            $jsonLd['aggregateRating'] = [
                "\x40type" => 'AggregateRating',
                'ratingValue' => number_format((float) $product->rating, 1, '.', ''),
                'reviewCount' => $product->review_count,
            ];
        }
    ?>
    <?php $__env->startPush('seo'); ?>
    <script type="application/ld+json"><?php echo json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?></script>
    <?php $__env->stopPush(); ?>

    <div class="grid md:grid-cols-2 gap-8 lg:gap-12">
        <!-- Images -->
        <div class="space-y-4">
            <div class="relative aspect-square rounded-lg overflow-hidden bg-gray-100">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($images) > 0): ?>
                    <div x-data="{ currentImage: <?php if ((object) ('selectedImage') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedImage'->value()); ?>')<?php echo e('selectedImage'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('selectedImage'); ?>')<?php endif; ?> }" x-init="$watch('currentImage', () => {
                        const container = $el.querySelector('.image-slider');
                        container.style.transform = `translateX(-${currentImage * 100}%)`;
                    })" class="w-full h-full overflow-hidden">
                        <div class="image-slider flex transition-transform duration-500 ease-in-out h-full" :style="`transform: translateX(-${currentImage * 100}%)`">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                                <div class="w-full h-full shrink-0">
                                    <img src="<?php echo e($img); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover" />
                                </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($discountPercent > 0): ?>
                    <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">-<?php echo e($discountPercent); ?>%</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($images) > 1): ?>
                <div class="flex gap-2 overflow-x-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $img): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <button wire:click="selectImage(<?php echo e($idx); ?>)" class="relative w-20 h-20 rounded-md overflow-hidden border-2 shrink-0 <?php echo e($selectedImage === $idx ? 'border-[hsl(var(--primary))]' : 'border-transparent'); ?> transition-all duration-200 hover:opacity-80">
                            <img src="<?php echo e($img); ?>" alt="<?php echo e($product->name); ?> <?php echo e($idx + 1); ?>" class="w-full h-full object-cover" />
                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Product Info -->
        <div class="space-y-5">
            <!-- Category & Type Badge -->
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <a href="<?php echo e(route('products', ['category' => $product->category->slug])); ?>" class="text-sm text-gray-500 hover:text-[hsl(var(--primary))] transition-colors"><?php echo e($product->category->name); ?></a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->product_type !== 'book'): ?>
                        <?php
                            $typeBadgeClass = match($product->product_type) {
                                'clothing' => 'bg-purple-100 text-purple-700',
                                default => 'bg-gray-100 text-gray-600',
                            };
                        ?>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full <?php echo e($typeBadgeClass); ?>"><?php echo e($product->product_type_label); ?></span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 leading-tight"><?php echo e($product->name); ?></h1>
            </div>

            <!-- Rating & Sales -->
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex items-center gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($star = 1; $star <= 5; $star++): ?>
                        <svg class="h-4 w-4 <?php echo e($star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200'); ?>" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <span class="ml-1 text-sm font-semibold text-gray-700"><?php echo e($product->rating); ?></span>
                </div>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500"><?php echo e($product->review_count); ?> รีวิว</span>
                <span class="text-gray-300">|</span>
                <span class="text-sm text-gray-500">ขายแล้ว <?php echo e(number_format($product->sold)); ?> ชิ้น</span>
            </div>

            <!-- Price -->
            <?php
                $hasVariants = $product->isClothing() && $product->variants->isNotEmpty();
            ?>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl font-bold text-[hsl(var(--primary))]">฿<?php echo e(number_format($product->price, 0)); ?></span>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->original_price): ?>
                        <span class="text-lg text-gray-400 line-through">฿<?php echo e(number_format($product->original_price, 0)); ?></span>
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">-<?php echo e($discountPercent); ?>%</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVariants): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedSize || $selectedColor): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentVariantStock > 0): ?>
                            <p class="text-xs text-green-600 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                มีสินค้า (คงเหลือ <?php echo e($currentVariantStock); ?> ชิ้น)
                            </p>
                        <?php else: ?>
                            <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                                ตัวเลือกนี้สินค้าหมด
                            </p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php else: ?>
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            กรุณาเลือกไซส์/สี เพื่อดูจำนวนสินค้าคงเหลือ
                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php elseif($product->stock > 0): ?>
                    <p class="text-xs text-green-600 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        มีสินค้า (คงเหลือ <?php echo e($product->stock); ?> ชิ้น)
                    </p>
                <?php else: ?>
                    <p class="text-xs text-red-500 mt-1.5 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        สินค้าหมด
                    </p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Book Info -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->product_type === 'book' && ($product->publisher || !empty($product->authors) || !empty($product->genres) || $product->pages)): ?>
            <div class="rounded-xl border border-blue-100 bg-blue-50/40 p-4">
                <h3 class="text-sm font-semibold text-blue-800 flex items-center gap-1.5 mb-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                    ข้อมูลหนังสือ
                </h3>
                <div class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->publisher): ?>
                        <div><span class="text-gray-400">สำนักพิมพ์</span></div>
                        <div class="font-medium text-gray-700"><?php echo e($product->publisher); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($product->authors)): ?>
                        <div><span class="text-gray-400">ผู้แต่ง</span></div>
                        <div class="font-medium text-gray-700"><?php echo e(implode(', ', $product->authors)); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($product->genres)): ?>
                        <div><span class="text-gray-400">หมวดหมู่</span></div>
                        <div class="font-medium text-gray-700"><?php echo e(implode(', ', $product->genres)); ?></div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->pages): ?>
                        <div><span class="text-gray-400">จำนวนหน้า</span></div>
                        <div class="font-medium text-gray-700"><?php echo e(number_format($product->pages)); ?> หน้า</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Clothing: Size Selector -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->isClothing() && !empty($product->sizes)): ?>
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-sm font-semibold text-gray-800">ขนาด / ไซส์</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedSize): ?>
                        <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: <?php echo e($selectedSize); ?></span>
                    <?php else: ?>
                        <span class="text-xs text-red-500">* กรุณาเลือก</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $product->sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            // Calculate total stock for this size across all colors
                            $sizeStock = 0;
                            if ($hasVariants) {
                                $sizeStock = $product->variants->where('size', $size)->where('is_active', true)->sum('stock');
                            }
                            $sizeOutOfStock = $hasVariants && $sizeStock <= 0;
                        ?>
                        <button
                            wire:click="$set('selectedSize', '<?php echo e($size); ?>')"
                            <?php if($sizeOutOfStock): ?> disabled <?php endif; ?>
                            class="relative min-w-12 px-4 py-2.5 rounded-lg border-2 text-sm font-medium transition-all duration-200
                                <?php echo e($sizeOutOfStock
                                    ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed line-through'
                                    : ($selectedSize === $size
                                        ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 text-[hsl(var(--primary))] shadow-sm ring-1 ring-[hsl(var(--primary))]/20'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-400 hover:bg-gray-50')); ?>"
                        >
                            <?php echo e($size); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVariants && !$sizeOutOfStock): ?>
                                <span class="block text-[10px] font-normal <?php echo e($selectedSize === $size ? 'text-[hsl(var(--primary))]/70' : 'text-gray-400'); ?>">เหลือ <?php echo e($sizeStock); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sizeOutOfStock): ?>
                                <span class="block text-[10px] font-normal text-gray-300">หมด</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedSize === $size && !$sizeOutOfStock): ?>
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[hsl(var(--primary))] rounded-full flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Clothing: Color Selector -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->isClothing() && !empty($product->colors)): ?>
            <div>
                <div class="flex items-center justify-between mb-2.5">
                    <h3 class="text-sm font-semibold text-gray-800">สี / ลาย</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedColor): ?>
                        <span class="text-xs text-[hsl(var(--primary))] font-medium">เลือกแล้ว: <?php echo e($selectedColor); ?></span>
                    <?php else: ?>
                        <span class="text-xs text-red-500">* กรุณาเลือก</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $product->colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <?php
                            // Calculate stock for this color (filtered by selected size if any)
                            $colorStock = 0;
                            if ($hasVariants) {
                                $colorVariants = $product->variants->where('color', $color)->where('is_active', true);
                                if ($selectedSize) {
                                    $colorVariants = $colorVariants->where('size', $selectedSize);
                                }
                                $colorStock = $colorVariants->sum('stock');
                            }
                            $colorOutOfStock = $hasVariants && $colorStock <= 0;
                        ?>
                        <button
                            wire:click="$set('selectedColor', '<?php echo e($color); ?>')"
                            <?php if($colorOutOfStock): ?> disabled <?php endif; ?>
                            class="relative px-4 py-2.5 rounded-lg border-2 text-sm font-medium transition-all duration-200
                                <?php echo e($colorOutOfStock
                                    ? 'border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed line-through'
                                    : ($selectedColor === $color
                                        ? 'border-[hsl(var(--primary))] bg-[hsl(var(--primary))]/5 text-[hsl(var(--primary))] shadow-sm ring-1 ring-[hsl(var(--primary))]/20'
                                        : 'border-gray-200 text-gray-700 hover:border-gray-400 hover:bg-gray-50')); ?>"
                        >
                            <?php echo e($color); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasVariants && !$colorOutOfStock): ?>
                                <span class="block text-[10px] font-normal <?php echo e($selectedColor === $color ? 'text-[hsl(var(--primary))]/70' : 'text-gray-400'); ?>">เหลือ <?php echo e($colorStock); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($colorOutOfStock): ?>
                                <span class="block text-[10px] font-normal text-gray-300">หมด</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedColor === $color && !$colorOutOfStock): ?>
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-[hsl(var(--primary))] rounded-full flex items-center justify-center">
                                    <svg class="w-2.5 h-2.5 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"/></svg>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Clothing: Material -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->isClothing() && $product->material): ?>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                <span class="text-gray-400">เนื้อผ้า:</span>
                <span class="font-medium"><?php echo e($product->material); ?></span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Description -->
            <div>
                <h3 class="text-sm font-semibold text-gray-800 mb-2">รายละเอียดสินค้า</h3>
                <p class="text-sm text-gray-500 leading-relaxed"><?php echo e($product->description); ?></p>
            </div>

            <!-- SKU & Weight -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->weight || $product->sku): ?>
            <div class="flex flex-wrap gap-x-6 gap-y-1 text-xs text-gray-400">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->sku): ?>
                    <span>SKU: <?php echo e($product->sku); ?></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->weight): ?>
                    <span>น้ำหนัก: <?php echo e(number_format($product->weight)); ?> กรัม</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <hr class="border-gray-100">

            <!-- Quantity -->
            <div class="flex items-center gap-4">
                <span class="text-sm font-semibold text-gray-800">จำนวน</span>
                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                    <button wire:click="decrementQty" class="h-10 w-10 flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 transition-colors <?php echo e($quantity <= 1 ? 'opacity-40 cursor-not-allowed' : ''); ?>">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>
                    </button>
                    <span class="w-12 text-center font-semibold text-gray-800 border-x border-gray-200"><?php echo e($quantity); ?></span>
                    <button wire:click="incrementQty" class="h-10 w-10 flex items-center justify-center hover:bg-gray-50 active:bg-gray-100 transition-colors <?php echo e($quantity >= $currentVariantStock ? 'opacity-40 cursor-not-allowed' : ''); ?>">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5v14"/></svg>
                    </button>
                </div>
            </div>

            <!-- Actions -->
            <?php $canAddToCart = $currentVariantStock > 0; ?>
            <div class="flex gap-3">
                <button wire:click="addToCart" wire:loading.attr="disabled" wire:target="addToCart" class="flex-1 flex items-center justify-center gap-2 py-3.5 bg-[hsl(var(--primary))] text-white rounded-xl font-semibold hover:opacity-90 active:scale-[0.98] transition-all <?php echo e(!$canAddToCart ? 'opacity-50 cursor-not-allowed' : ''); ?>" <?php echo e(!$canAddToCart ? 'disabled' : ''); ?>>
                    <svg wire:loading.remove wire:target="addToCart" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 002 1.58h9.78a2 2 0 001.95-1.57l1.65-7.43H5.12"/></svg>
                    <svg wire:loading wire:target="addToCart" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    <span wire:loading.remove wire:target="addToCart">เพิ่มลงตะกร้า</span>
                    <span wire:loading wire:target="addToCart">กำลังเพิ่ม...</span>
                </button>
                <button wire:click="buyNow" wire:loading.attr="disabled" wire:target="buyNow" class="flex-1 py-3.5 border-2 border-[hsl(var(--primary))] text-[hsl(var(--primary))] rounded-xl font-semibold hover:bg-[hsl(var(--primary))]/5 active:scale-[0.98] transition-all <?php echo e(!$canAddToCart ? 'opacity-50 cursor-not-allowed' : ''); ?>" <?php echo e(!$canAddToCart ? 'disabled' : ''); ?>>
                    <span wire:loading.remove wire:target="buyNow">ซื้อเลย</span>
                    <span wire:loading wire:target="buyNow" class="inline-flex items-center gap-2">
                        <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                        กำลังดำเนินการ...
                    </span>
                </button>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('wishlist-button', ['product-id' => $product->id,'size' => 'md']);

$key = 'wl-detail-'.$product->id;
$__componentSlots = [];

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3041344655-0', $key);

$__html = app('livewire')->mount($__name, $__params, $key, $__componentSlots);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
            </div>

            <!-- Features -->
            <div class="grid grid-cols-3 gap-3">
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <svg class="h-5 w-5 text-[hsl(var(--primary))]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    <span class="text-xs font-medium text-gray-700">จัดส่ง 1-3 วัน</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <svg class="h-5 w-5 text-[hsl(var(--primary))]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span class="text-xs font-medium text-gray-700">สินค้าแท้ 100%</span>
                </div>
                <div class="flex flex-col items-center gap-1.5 py-3 px-2 bg-gray-50 rounded-xl">
                    <svg class="h-5 w-5 text-[hsl(var(--primary))]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 14l-4 4m0 0l4 4m-4-4h11a4 4 0 000-8h-1"/></svg>
                    <span class="text-xs font-medium text-gray-700">คืนใน 7 วัน</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <section class="mt-16">
        <h2 class="text-2xl font-bold mb-6">รีวิวจากลูกค้า (<?php echo e($product->review_count); ?>)</h2>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Rating Summary -->
            <div class="bg-gray-50 rounded-xl p-6 text-center">
                <p class="text-5xl font-bold text-gray-800"><?php echo e(number_format($product->rating, 1)); ?></p>
                <div class="flex justify-center gap-0.5 mt-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($star = 1; $star <= 5; $star++): ?>
                        <svg class="h-5 w-5 <?php echo e($star <= round($product->rating) ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300'); ?>" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <p class="text-sm text-gray-500 mt-1">จาก <?php echo e($product->review_count); ?> รีวิว</p>

                <?php
                    $reviews = $product->reviews;
                    $ratingCounts = [];
                    for ($i = 5; $i >= 1; $i--) {
                        $ratingCounts[$i] = $reviews->where('rating', $i)->count();
                    }
                ?>
                <div class="mt-4 space-y-1.5 text-left">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 5; $i >= 1; $i--): ?>
                        <div class="flex items-center gap-2 text-xs">
                            <span class="w-3 text-gray-500"><?php echo e($i); ?></span>
                            <svg class="h-3 w-3 text-yellow-400 fill-yellow-400 shrink-0" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                            <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-400 rounded-full" style="width: <?php echo e($product->review_count > 0 ? ($ratingCounts[$i] / $product->review_count * 100) : 0); ?>%"></div>
                            </div>
                            <span class="w-6 text-right text-gray-400"><?php echo e($ratingCounts[$i]); ?></span>
                        </div>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Write Review Button -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->guard()->check()): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canReview): ?>
                        <button wire:click="toggleReviewForm" class="mt-5 w-full py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                            เขียนรีวิว
                        </button>
                    <?php elseif($hasReviewed): ?>
                        <p class="mt-5 text-xs text-gray-400">คุณรีวิวสินค้านี้แล้ว</p>
                    <?php else: ?>
                        <p class="mt-5 text-xs text-gray-400">ซื้อสินค้านี้แล้วจึงจะรีวิวได้</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="mt-5 block w-full py-2.5 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors text-center">
                        เข้าสู่ระบบเพื่อรีวิว
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Reviews List -->
            <div class="md:col-span-2">
                <!-- Review Form -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showReviewForm): ?>
                    <div class="border border-teal-200 bg-teal-50/30 rounded-xl p-5 mb-6">
                        <h3 class="font-semibold text-gray-800 mb-4">เขียนรีวิวของคุณ</h3>
                        <form wire:submit="submitReview" class="space-y-4">
                            <!-- Star Rating -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">ให้คะแนน</label>
                                <div class="flex gap-1" x-data>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($star = 1; $star <= 5; $star++): ?>
                                        <button type="button" wire:click="setRating(<?php echo e($star); ?>)" class="focus:outline-none transition-transform hover:scale-110">
                                            <svg class="h-8 w-8 cursor-pointer <?php echo e($star <= $reviewRating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-300 hover:text-yellow-300'); ?> transition-colors" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </button>
                                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="ml-2 text-sm text-gray-500 self-center">
                                        <?php
                                            $ratingLabels = [1 => 'แย่มาก', 2 => 'แย่', 3 => 'ปานกลาง', 4 => 'ดี', 5 => 'ดีมาก'];
                                        ?>
                                        <?php echo e($ratingLabels[$reviewRating] ?? ''); ?>

                                    </span>
                                </div>
                            </div>

                            <!-- Comment -->
                            <div>
                                <label class="text-sm font-medium text-gray-700 mb-2 block">รีวิว</label>
                                <textarea wire:model="reviewComment" rows="4" placeholder="แชร์ประสบการณ์ของคุณเกี่ยวกับสินค้านี้..." class="w-full px-4 py-3 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-gray-400 focus:ring-1 focus:ring-gray-300 transition resize-none"></textarea>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['reviewComment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-red-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-2.5 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors">
                                    ส่งรีวิว
                                </button>
                                <button type="button" wire:click="toggleReviewForm" class="px-6 py-2.5 border border-gray-200 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
                                    ยกเลิก
                                </button>
                            </div>
                        </form>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($product->reviews->count() > 0): ?>
                    <div class="space-y-5">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $product->reviews->sortByDesc('created_at'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                            <div class="border rounded-xl p-5 space-y-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($review->user && ($review->user->social_avatar || $review->user->avatar)): ?>
                                            <img src="<?php echo e($review->user->social_avatar ?: $review->user->avatar); ?>" alt="" class="w-10 h-10 rounded-full object-cover ring-2 ring-gray-100" referrerpolicy="no-referrer" />
                                        <?php else: ?>
                                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-sm font-bold text-gray-600">
                                                <?php echo e(strtoupper(mb_substr($review->user->name ?? 'U', 0, 1))); ?>

                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div>
                                            <p class="font-medium text-sm"><?php echo e($review->user->name ?? 'ผู้ใช้'); ?></p>
                                            <p class="text-xs text-gray-400"><?php echo e($review->created_at->locale('th')->diffForHumans()); ?></p>
                                        </div>
                                    </div>
                                    <div class="flex gap-0.5">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($star = 1; $star <= 5; $star++): ?>
                                            <svg class="h-4 w-4 <?php echo e($star <= $review->rating ? 'text-yellow-400 fill-yellow-400' : 'text-gray-200'); ?>" viewBox="0 0 24 24"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                                <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($review->comment); ?></p>
                            </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                        <p class="text-gray-400 text-sm">ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้!</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Related Products -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($relatedProducts) > 0): ?>
        <section class="mt-16">
            <h2 class="text-2xl font-bold mb-6">สินค้าที่เกี่ยวข้อง</h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php echo $__env->make('partials.product-card', ['product' => $rp], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('stock-exceeded', () => {
        Swal.fire({
            icon: 'warning',
            title: 'มีข้อผิดพลาด',
            text: 'จำนวนที่คุณเลือกเกินกว่าสต็อกที่มีอยู่ กรุณาเลือกจำนวนที่น้อยลง',
            confirmButtonColor: 'hsl(var(--primary))',
            confirmButtonText: 'ตกลง'
        });
    });
});
</script>
<?php /**PATH C:\Project\pgmfshop\backend\resources\views/livewire/product-detail.blade.php ENDPATH**/ ?>