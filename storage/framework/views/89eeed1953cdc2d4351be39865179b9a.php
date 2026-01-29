<div class="mb-3 text-start position-relative">
    <label class="form-label"><?php echo e($label); ?></label>
    <input 
        type="<?php echo e($type ?? 'text'); ?>" 
        name="<?php echo e($name); ?>" 
        class="form-control rounded <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
        placeholder="<?php echo e($placeholder ?? ''); ?>" 
        value="<?php echo e(old($name)); ?>"
        <?php echo e($attributes); ?>

    >
    
    <?php echo e($slot); ?>


    <?php $__errorArgs = [$name];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
        <div class="text-danger small mt-1"><?php echo e($message); ?></div>
    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
</div><?php /**PATH /home/adxventure/lara_tms/resources/views/components/form/form-input.blade.php ENDPATH**/ ?>