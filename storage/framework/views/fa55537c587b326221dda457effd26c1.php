

<?php $__env->startSection('title', 'Delivery Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1>Delivery Fee Split Settings</h1>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('admin.delivery-settings.update')); ?>" method="POST">
                <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                <div class="mb-3">
                    <label>Dispatch Percentage (%)</label>
                    <input type="number" name="dispatch_percentage" 
                           value="<?php echo e($settings->dispatch_percentage); ?>" 
                           class="form-control" min="0" max="100">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/delivery-settings/index.blade.php ENDPATH**/ ?>