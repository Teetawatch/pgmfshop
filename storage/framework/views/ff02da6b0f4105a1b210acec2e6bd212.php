<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['type' => 'line', 'count' => 1, 'cols' => '', 'class' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['type' => 'line', 'count' => 1, 'cols' => '', 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($type):
    
    case ('product-card'): ?>
        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 h-full flex flex-col animate-pulse">
            
            <div class="aspect-square bg-gray-200"></div>
            
            <div class="p-3 flex-1 flex flex-col gap-1.5">
                
                <div class="space-y-1">
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-3/4"></div>
                </div>
                
                <div class="mt-auto">
                    <div class="flex items-baseline gap-2">
                        <div class="h-4 sm:h-5 bg-gray-200 rounded w-16"></div>
                        <div class="h-3 bg-gray-200 rounded w-10"></div>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 5; $i++): ?>
                            <div class="h-2.5 w-2.5 bg-gray-200 rounded-full"></div>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="h-2.5 bg-gray-200 rounded w-12 ml-0.5"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php break; ?>

    
    <?php case ('product-grid'): ?>
        <?php
            $gridCols = $cols ?: 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4';
        ?>
        <div class="grid <?php echo e($gridCols); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $count; $i++): ?>
                <?php if (isset($component)) { $__componentOriginal31de594fa8b31c89482b92f93a0b9eeb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton','data' => ['type' => 'product-card']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'product-card']); ?>
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
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php break; ?>

    
    <?php case ('product-detail'): ?>
        <div class="grid md:grid-cols-2 gap-8 lg:gap-12 animate-pulse">
            
            <div class="space-y-4">
                <div class="aspect-square rounded-lg bg-gray-200"></div>
                <div class="flex gap-2 overflow-x-auto">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 4; $i++): ?>
                        <div class="w-20 h-20 rounded-md bg-gray-200 shrink-0"></div>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            
            <div class="space-y-6">
                
                <div>
                    <div class="h-3.5 bg-gray-200 rounded w-20 mb-1"></div>
                    <div class="h-7 md:h-9 bg-gray-200 rounded w-4/5"></div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 5; $i++): ?>
                            <div class="h-5 w-5 bg-gray-200 rounded-sm"></div>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="h-4 bg-gray-200 rounded w-6 ml-1"></div>
                    </div>
                    <div class="h-3.5 bg-gray-200 rounded w-32"></div>
                </div>
                
                <div class="flex items-baseline gap-3">
                    <div class="h-9 bg-gray-200 rounded w-28"></div>
                    <div class="h-5 bg-gray-200 rounded w-16"></div>
                    <div class="h-5 bg-gray-200 rounded w-12"></div>
                </div>
                <div class="h-px bg-gray-200"></div>
                
                <div>
                    <div class="h-5 bg-gray-200 rounded w-32 mb-2"></div>
                    <div class="space-y-1.5">
                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                        <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    </div>
                </div>
                
                <div class="space-y-1.5">
                    <div class="h-3.5 bg-gray-200 rounded w-48"></div>
                    <div class="h-3.5 bg-gray-200 rounded w-40"></div>
                    <div class="h-3.5 bg-gray-200 rounded w-32"></div>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="h-4 bg-gray-200 rounded w-10"></div>
                    <div class="h-5 bg-gray-200 rounded w-24"></div>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="h-4 bg-gray-200 rounded w-12"></div>
                    <div class="h-10 w-28 bg-gray-200 rounded-md"></div>
                </div>
                
                <div class="flex gap-3">
                    <div class="flex-1 h-12 bg-gray-200 rounded-md"></div>
                    <div class="flex-1 h-12 bg-gray-200 rounded-md"></div>
                    <div class="w-12 h-12 bg-gray-200 rounded-md"></div>
                </div>
                <div class="h-px bg-gray-200"></div>
                
                <div class="grid grid-cols-3 gap-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 3; $i++): ?>
                        <div class="text-center space-y-1">
                            <div class="h-6 w-6 bg-gray-200 rounded mx-auto"></div>
                            <div class="h-4 bg-gray-200 rounded w-16 mx-auto"></div>
                            <div class="h-3 bg-gray-200 rounded w-10 mx-auto"></div>
                        </div>
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
        <?php break; ?>

    <?php case ('banner'): ?>
        <div class="rounded-xl overflow-hidden bg-gray-200 h-[220px] sm:h-[300px] md:h-[400px] animate-pulse"></div>
        <?php break; ?>

    
    <?php case ('cart-item'): ?>
        <div class="bg-white rounded-lg border p-4 animate-pulse">
            <div class="flex gap-4">
                <div class="w-24 h-24 rounded-md bg-gray-200 shrink-0"></div>
                <div class="flex-1 min-w-0">
                    
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    
                    <div class="h-3 bg-gray-200 rounded w-1/4 mt-1"></div>
                    
                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center border border-gray-200 rounded-md">
                            <div class="h-8 w-8 bg-gray-100"></div>
                            <div class="w-8 h-4 bg-gray-200 mx-1"></div>
                            <div class="h-8 w-8 bg-gray-100"></div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="h-5 bg-gray-200 rounded w-16"></div>
                            <div class="h-8 w-8 bg-gray-200 rounded-md"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php break; ?>

    
    <?php case ('cart-page'): ?>
        <div class="animate-pulse">
            <div class="h-9 bg-gray-200 rounded w-40 mb-8"></div>
            <div class="grid lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $count; $i++): ?>
                        <?php if (isset($component)) { $__componentOriginal31de594fa8b31c89482b92f93a0b9eeb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal31de594fa8b31c89482b92f93a0b9eeb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.skeleton','data' => ['type' => 'cart-item']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('skeleton'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'cart-item']); ?>
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
                    <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="flex justify-between">
                        <div class="h-9 bg-gray-200 rounded w-28"></div>
                        <div class="h-9 bg-gray-200 rounded w-24"></div>
                    </div>
                </div>
                <div>
                    <div class="bg-white rounded-lg border p-6 space-y-4">
                        <div class="h-6 bg-gray-200 rounded w-32"></div>
                        <div class="flex gap-2">
                            <div class="flex-1 h-10 bg-gray-200 rounded-md"></div>
                            <div class="h-10 w-14 bg-gray-200 rounded-md"></div>
                        </div>
                        <div class="h-px bg-gray-200"></div>
                        <div class="space-y-2">
                            <div class="flex justify-between"><div class="h-4 bg-gray-200 rounded w-16"></div><div class="h-4 bg-gray-200 rounded w-14"></div></div>
                            <div class="flex justify-between"><div class="h-4 bg-gray-200 rounded w-24"></div><div class="h-4 bg-gray-200 rounded w-10"></div></div>
                        </div>
                        <div class="h-px bg-gray-200"></div>
                        <div class="flex justify-between"><div class="h-6 bg-gray-200 rounded w-20"></div><div class="h-6 bg-gray-200 rounded w-16"></div></div>
                        <div class="h-12 bg-gray-200 rounded-md w-full"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php break; ?>

    
    <?php case ('order-item'): ?>
        <div class="bg-white rounded-lg border animate-pulse">
            
            <div class="px-5 py-4 border-b flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-3">
                    <div class="h-4 bg-gray-200 rounded w-36"></div>
                    <div class="h-5 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-3.5 bg-gray-200 rounded w-40"></div>
            </div>
            
            <div class="px-5 py-4 space-y-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 2; $i++): ?>
                    <div class="flex gap-3 items-center">
                        <div class="w-14 h-14 rounded-md bg-gray-200 shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="h-3.5 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 rounded w-8 mt-1"></div>
                        </div>
                        <div class="h-3.5 bg-gray-200 rounded w-14 shrink-0"></div>
                    </div>
                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            <div class="px-5 py-3 bg-gray-50 border-t flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <div class="h-3.5 bg-gray-200 rounded w-8"></div>
                    <div class="h-5 bg-gray-200 rounded w-20"></div>
                </div>
                <div class="h-9 bg-gray-200 rounded-md w-28"></div>
            </div>
        </div>
        <?php break; ?>

    
    <?php case ('wishlist-card'): ?>
        <div class="bg-white rounded-lg overflow-hidden border border-gray-100 flex flex-col animate-pulse">
            <div class="aspect-square bg-gray-200"></div>
            <div class="p-3 flex-1 flex flex-col gap-1.5">
                <div class="space-y-1">
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-full"></div>
                    <div class="h-3 sm:h-3.5 bg-gray-200 rounded w-2/3"></div>
                </div>
                <div class="h-2.5 bg-gray-200 rounded w-16"></div>
                <div class="mt-auto">
                    <div class="flex items-baseline gap-2">
                        <div class="h-4 sm:h-5 bg-gray-200 rounded w-16"></div>
                        <div class="h-3 bg-gray-200 rounded w-10"></div>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < 5; $i++): ?>
                            <div class="h-2.5 w-2.5 bg-gray-200 rounded-full"></div>
                        <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="h-2.5 bg-gray-200 rounded w-6 ml-0.5"></div>
                    </div>
                </div>
                
                <div class="flex gap-2 mt-2 pt-2 border-t border-gray-100">
                    <div class="flex-1 h-9 bg-gray-200 rounded-lg"></div>
                    <div class="h-9 w-9 bg-gray-200 rounded-lg"></div>
                </div>
            </div>
        </div>
        <?php break; ?>

    <?php case ('table-row'): ?>
        <tr class="animate-pulse">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $count; $i++): ?>
                <td class="px-4 py-3"><div class="h-4 bg-gray-200 rounded w-<?php echo e(['full', '3/4', '1/2', '1/3', '2/3'][$i % 5]); ?>"></div></td>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </tr>
        <?php break; ?>

    <?php case ('line'): ?>
    <?php default: ?>
        <div class="animate-pulse <?php echo e($class); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($i = 0; $i < $count; $i++): ?>
                <div class="h-4 bg-gray-200 rounded <?php echo e($i < $count - 1 ? 'mb-2 w-full' : 'w-2/3'); ?>"></div>
            <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php break; ?>
<?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\Project\pgmfshop\backend\resources\views/components/skeleton.blade.php ENDPATH**/ ?>