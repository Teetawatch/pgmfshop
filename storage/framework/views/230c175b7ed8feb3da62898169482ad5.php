<div class="bg-gray-50 min-h-screen">
    <!-- Hero Banner Slider -->
    <section class="container mx-auto px-4 pt-4 pb-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->count() > 0): ?>
        <div x-data="bannerSlider(<?php echo e($banners->count()); ?>)" x-init="startAutoSlide()" class="relative rounded-2xl overflow-hidden h-[200px] sm:h-[320px] md:h-[420px] lg:h-[480px] group shadow-lg shadow-gray-300/40">
            <!-- Slides -->
            <div class="relative w-full h-full">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                <div x-show="current === <?php echo e($idx); ?>"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 scale-[1.02]"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-500"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="absolute inset-0">
                    <img src="<?php echo e($banner->image); ?>" alt="<?php echo e($banner->title); ?>" class="w-full h-full object-cover">
                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-linear-to-r from-black/60 via-black/30 to-transparent"></div>
                    <div class="absolute inset-0 bg-linear-to-t from-black/40 via-transparent to-transparent"></div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->title || $banner->subtitle || $banner->button_text): ?>
                    <div class="absolute inset-0 flex items-center">
                        <div class="px-5 sm:px-8 md:px-12 text-white space-y-2 sm:space-y-3 md:space-y-4 max-w-xl">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->title): ?>
                                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight drop-shadow-lg">
                                    <?php echo e($banner->title); ?>

                                </h2>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->subtitle): ?>
                                <p class="text-xs sm:text-sm md:text-base text-white/90 max-w-md leading-relaxed drop-shadow"><?php echo e($banner->subtitle); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banner->button_text && $banner->button_link): ?>
                                <a href="<?php echo e($banner->button_link); ?>" class="inline-flex items-center gap-2 bg-white text-gray-900 hover:bg-gray-100 text-xs sm:text-sm px-5 sm:px-6 py-2 sm:py-2.5 rounded-lg font-semibold mt-1 transition-all shadow-lg hover:shadow-xl group/btn">
                                    <?php echo e($banner->button_text); ?>

                                    <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                                </a>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($banners->count() > 1): ?>
            <!-- Prev/Next Arrows -->
            <button @click="prev()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-300 z-10 hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button @click="next()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-10 sm:h-10 bg-white/90 hover:bg-white rounded-full flex items-center justify-center shadow-lg sm:opacity-0 sm:group-hover:opacity-100 transition-all duration-300 z-10 hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-700" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Slide Counter + Dots -->
            <div class="absolute bottom-3 sm:bottom-4 left-0 right-0 flex items-center justify-center gap-3 z-10 px-4">
                <!-- Dots -->
                <div class="flex gap-1.5 sm:gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <button @click="goTo(<?php echo e($idx); ?>)" :class="current === <?php echo e($idx); ?> ? 'bg-white w-5 sm:w-7' : 'bg-white/40 w-2 sm:w-2.5 hover:bg-white/60'"
                        class="h-2 sm:h-2.5 rounded-full transition-all duration-300"></button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
                <!-- Counter -->
                <span class="hidden sm:inline-flex items-center gap-1 text-[11px] text-white/80 font-medium bg-black/30 backdrop-blur-sm px-2.5 py-1 rounded-full">
                    <span x-text="current + 1"></span>/<span><?php echo e($banners->count()); ?></span>
                </span>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php else: ?>
        
        <div class="relative rounded-2xl overflow-hidden h-[200px] sm:h-[320px] md:h-[420px] lg:h-[480px] shadow-lg shadow-gray-300/40">
            <div class="absolute inset-0 bg-linear-to-br from-orange-500 via-orange-600 to-red-600"></div>
            <!-- Decorative circles -->
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/10 rounded-full"></div>
            <div class="absolute -bottom-16 -left-16 w-48 h-48 bg-white/10 rounded-full"></div>
            <div class="absolute top-1/2 right-1/4 w-32 h-32 bg-white/5 rounded-full"></div>
            <div class="absolute inset-0 flex items-center">
                <div class="px-6 sm:px-10 md:px-12 z-10 text-white space-y-3 sm:space-y-4 max-w-lg">
                    <span class="inline-block text-[10px] sm:text-xs font-bold uppercase tracking-widest bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full">ยินดีต้อนรับ</span>
                    <h2 class="text-2xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">PGMF SHOP</h2>
                    <p class="text-sm sm:text-base text-white/90 max-w-sm leading-relaxed">สินค้าคุณภาพ ราคาดี จัดส่งรวดเร็ว พร้อมโปรโมชั่นสุดพิเศษ</p>
                    <a href="<?php echo e(route('products')); ?>" class="inline-flex items-center gap-2 bg-white text-orange-600 hover:bg-gray-100 text-xs sm:text-sm px-6 py-2.5 rounded-lg font-semibold transition-all shadow-lg hover:shadow-xl group/btn">
                        ช้อปเลย
                        <svg class="w-3.5 h-3.5 transition-transform group-hover/btn:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M5 12h14m-7-7 7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <script>
        function bannerSlider(total) {
            return {
                current: 0,
                count: total,
                interval: null,
                next() { this.current = (this.current + 1) % this.count; this.resetAutoSlide(); },
                prev() { this.current = (this.current - 1 + this.count) % this.count; this.resetAutoSlide(); },
                goTo(i) { this.current = i; this.resetAutoSlide(); },
                startAutoSlide() { this.interval = setInterval(() => { this.current = (this.current + 1) % this.count; }, 5000); },
                resetAutoSlide() { clearInterval(this.interval); this.startAutoSlide(); },
            }
        }
    </script>


    <!-- Product Tabs + Grid -->
    <section class="container mx-auto px-4 pb-8">
        <!-- Tabs -->
        <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
            <div class="flex items-center gap-1">
                <h2 class="text-base sm:text-lg font-bold text-gray-800 mr-3">สินค้าทั้งหมด</h2>
                <div class="flex gap-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = [['all', 'สินค้าทั้งหมด'], ['hot', 'HOT'], ['new', 'NEW'], ['recommended', 'RECOMMENDED']]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$key, $label]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <button wire:click="setTab('<?php echo e($key); ?>')"
                            class="px-3 py-1.5 rounded-md text-xs font-medium transition-colors <?php echo e($activeTab === $key ? 'bg-[hsl(var(--primary))] text-white' : 'bg-white text-gray-500 hover:bg-gray-100 border border-gray-200'); ?>">
                            <?php echo e($label); ?>

                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <svg class="h-3.5 w-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 6h18M6 12h12M9 18h6"/></svg>
                <select wire:model.live="sortBy" class="text-xs sm:text-sm bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-gray-600 focus:outline-none focus:ring-1 focus:ring-gray-300 focus:border-gray-300 cursor-pointer">
                    <option value="default">เรียงตาม: ค่าเริ่มต้น</option>
                    <option value="price_asc">ราคาต่ำ → สูง</option>
                    <option value="price_desc">ราคาสูง → ต่ำ</option>
                    <option value="in_stock">สินค้าที่มีสต็อก</option>
                </select>
            </div>
        </div>

        <!-- Skeleton Loading -->
        <div wire:loading wire:target="setTab,sortBy">
            <?php if (isset($component)) { $__componentOriginal31de594fa8b31c89482b92f93a0b9eeb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton','data' => ['type' => 'product-grid','count' => 8]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'product-grid','count' => 8]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb)): ?>
<?php $attributes = $__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb; ?>
<?php unset($__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal31de594fa8b31c89482b92f93a0b9eeb)): ?>
<?php $component = $__componentOriginal31de594fa8b31c89482b92f93a0b9eeb; ?>
<?php unset($__componentOriginal31de594fa8b31c89482b92f93a0b9eeb); ?>
<?php endif; ?>
        </div>

        <div wire:loading.remove wire:target="setTab,sortBy">
            <!-- Product Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $products->take($visibleCount); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                    <?php echo $__env->make('partials.product-card', ['product' => $product], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($products->count() === 0): ?>
                <div class="text-center py-16 text-gray-500">ไม่พบสินค้า</div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Load More -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($visibleCount < $products->count()): ?>
                <div class="text-center mt-8">
                    <button wire:click="loadMore" class="px-8 py-2 rounded-full text-sm border border-gray-200 hover:bg-gray-50 transition-colors">Load More</button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </section>
</div>
<?php /**PATH C:\Project\pgmfshop\backend\resources\views/livewire/home-page.blade.php ENDPATH**/ ?>