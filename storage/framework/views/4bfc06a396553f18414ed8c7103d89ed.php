<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap/css/bootstrap.min.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(asset('assets/vendor/boxicons/css/boxicons.min.css')); ?>" />
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" />
        <link href="<?php echo e(asset('assets/vendor/toastr/toastr.min.css')); ?>" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <script src="<?php echo e(asset('assets/vendor/jquery/jquery.min.js')); ?>"></script>
        <script src="<?php echo e(asset('assets/vendor/toastr/toastr.min.js')); ?>"></script>
        <?php if (isset($component)) { $__componentOriginal68bd75f4c99daa8e3dc0395d952cb4c3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal68bd75f4c99daa8e3dc0395d952cb4c3 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.style','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.style'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal68bd75f4c99daa8e3dc0395d952cb4c3)): ?>
<?php $attributes = $__attributesOriginal68bd75f4c99daa8e3dc0395d952cb4c3; ?>
<?php unset($__attributesOriginal68bd75f4c99daa8e3dc0395d952cb4c3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal68bd75f4c99daa8e3dc0395d952cb4c3)): ?>
<?php $component = $__componentOriginal68bd75f4c99daa8e3dc0395d952cb4c3; ?>
<?php unset($__componentOriginal68bd75f4c99daa8e3dc0395d952cb4c3); ?>
<?php endif; ?>
        
        <?php echo $__env->yieldContent('css'); ?>
    </head>
        <?php if (isset($component)) { $__componentOriginal7a59feeeee8ce3f3cb3543e6971245f2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7a59feeeee8ce3f3cb3543e6971245f2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7a59feeeee8ce3f3cb3543e6971245f2)): ?>
<?php $attributes = $__attributesOriginal7a59feeeee8ce3f3cb3543e6971245f2; ?>
<?php unset($__attributesOriginal7a59feeeee8ce3f3cb3543e6971245f2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7a59feeeee8ce3f3cb3543e6971245f2)): ?>
<?php $component = $__componentOriginal7a59feeeee8ce3f3cb3543e6971245f2; ?>
<?php unset($__componentOriginal7a59feeeee8ce3f3cb3543e6971245f2); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginalbebe114f3ccde4b38d7462a3136be045 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalbebe114f3ccde4b38d7462a3136be045 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalbebe114f3ccde4b38d7462a3136be045)): ?>
<?php $attributes = $__attributesOriginalbebe114f3ccde4b38d7462a3136be045; ?>
<?php unset($__attributesOriginalbebe114f3ccde4b38d7462a3136be045); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalbebe114f3ccde4b38d7462a3136be045)): ?>
<?php $component = $__componentOriginalbebe114f3ccde4b38d7462a3136be045; ?>
<?php unset($__componentOriginalbebe114f3ccde4b38d7462a3136be045); ?>
<?php endif; ?>

        <main id="main" class="main">
            <?php echo e($slot); ?>

        </main>

        <?php if (isset($component)) { $__componentOriginal13a4d234756c16032caa3e2834ca83d8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal13a4d234756c16032caa3e2834ca83d8 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal13a4d234756c16032caa3e2834ca83d8)): ?>
<?php $attributes = $__attributesOriginal13a4d234756c16032caa3e2834ca83d8; ?>
<?php unset($__attributesOriginal13a4d234756c16032caa3e2834ca83d8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal13a4d234756c16032caa3e2834ca83d8)): ?>
<?php $component = $__componentOriginal13a4d234756c16032caa3e2834ca83d8; ?>
<?php unset($__componentOriginal13a4d234756c16032caa3e2834ca83d8); ?>
<?php endif; ?>
        <?php if (isset($component)) { $__componentOriginal11062c6e5c8cd46b44b4dc0a584f0f55 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal11062c6e5c8cd46b44b4dc0a584f0f55 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.script','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('admin.script'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal11062c6e5c8cd46b44b4dc0a584f0f55)): ?>
<?php $attributes = $__attributesOriginal11062c6e5c8cd46b44b4dc0a584f0f55; ?>
<?php unset($__attributesOriginal11062c6e5c8cd46b44b4dc0a584f0f55); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal11062c6e5c8cd46b44b4dc0a584f0f55)): ?>
<?php $component = $__componentOriginal11062c6e5c8cd46b44b4dc0a584f0f55; ?>
<?php unset($__componentOriginal11062c6e5c8cd46b44b4dc0a584f0f55); ?>
<?php endif; ?>
        <script>
            const settingsToggle = document.getElementById('settingsToggle');
            const settingsSidebar = document.getElementById('settingsSidebar');
            const settingsOverlay = document.getElementById('settingsOverlay');
            const settingsClose = document.getElementById('settingsClose');

            settingsToggle.addEventListener('click', () => {
                settingsSidebar.classList.add('show');
                settingsOverlay.classList.add('show');
            });

            function closeSettings() {
                settingsSidebar.classList.remove('show');
                settingsOverlay.classList.remove('show');
            }

            settingsClose.addEventListener('click', closeSettings);
            settingsOverlay.addEventListener('click', closeSettings);
        </script>

        <?php echo $__env->yieldContent('script'); ?>
    </body>
</html><?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/layouts/app.blade.php ENDPATH**/ ?>