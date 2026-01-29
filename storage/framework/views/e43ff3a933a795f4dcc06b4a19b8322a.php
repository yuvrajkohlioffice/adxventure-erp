<?php if(session('message')): ?>
<div class="alert alert-success" id="success-message">
    <?php echo e(session('message')); ?>

</div>
<?php endif; ?>
<?php if(session('success')): ?>
<div class="alert alert-success">
    <?php echo e(session('success')); ?>

</div>
<?php endif; ?>

<?php if(session('error')): ?>
<div class="alert alert-danger">
    <?php echo e(session('error')); ?>

</div>
<?php endif; ?>
<?php if(session('custom_errors')): ?>
<div class="alert alert-danger">
    <strong>Errors found in some rows:</strong>
    <ul>
        <?php $__currentLoopData = session('custom_errors'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li>
                <strong>Row:</strong>
                <?php echo e(is_array($error['row']) ? implode(', ', $error['row']) : $error['row']); ?>

                <ul>
                    <?php $__currentLoopData = $error['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $messages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <strong><?php echo e($field); ?>:</strong>
                            <?php echo e(is_array($messages) ? implode(', ', $messages) : $messages); ?>

                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ul>
</div>
<?php endif; ?>





<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    setTimeout(function() {
        $('#success-message').fadeOut('slow');
    }, 5000); // 2000 milliseconds = 2 seconds
});
$(document).ready(function() {
    setTimeout(function() {
        $('#danger-message').fadeOut('slow');
    }, 5000); // 2000 milliseconds = 2 seconds
});
</script><?php /**PATH /home/bookmziw/lara_tms/laravel/resources/views/include/alert.blade.php ENDPATH**/ ?>