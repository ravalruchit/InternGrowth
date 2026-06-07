<?php if (isset($component)) { $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.reputation-card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('reputation-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $attributes = $__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__attributesOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd)): ?>
<?php $component = $__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd; ?>
<?php unset($__componentOriginaldadd02ec3e07bc792adf86e9a3d9f3cd); ?>
<?php endif; ?><?php /**PATH C:\Users\Raval Ruchit\Desktop\InternGrowth\InternGrowth\storage\framework\views/d5f11a8e357304c7c04777a2bd1b61c6.blade.php ENDPATH**/ ?>