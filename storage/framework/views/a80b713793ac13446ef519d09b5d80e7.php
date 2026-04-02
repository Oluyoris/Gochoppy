

<?php $__env->startSection('title', 'Create New Coupon'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Create New Coupon</h2>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('admin.coupons.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control text-uppercase" 
                               placeholder="e.g. GO500" value="<?php echo e(old('code')); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Discount Amount (₦) <span class="text-danger">*</span></label>
                        <input type="number" name="discount_amount" class="form-control" 
                               placeholder="1000" value="<?php echo e(old('discount_amount')); ?>" min="100" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" 
                           placeholder="e.g. ₦1000 Off First Order" value="<?php echo e(old('title')); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Applicable Categories <span class="text-danger">*</span></label>
                    <div class="row">
                        <?php $__currentLoopData = ['kitchen', 'supermarket', 'pharmacy', 'dispatch']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           name="applicable_categories[]" value="<?php echo e($category); ?>"
                                           <?php echo e(in_array($category, old('applicable_categories', [])) ? 'checked' : ''); ?>>
                                    <label class="form-check-label"><?php echo e(ucfirst($category)); ?></label>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Maximum Uses <span class="text-danger">*</span></label>
                        <input type="number" name="max_uses" class="form-control" 
                               value="<?php echo e(old('max_uses', 10)); ?>" min="1" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Expiry Date (Optional)</label>
                        <input type="date" name="expires_at" class="form-control" 
                               value="<?php echo e(old('expires_at')); ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg">Create Coupon</button>
                <a href="<?php echo e(route('admin.coupons.index')); ?>" class="btn btn-secondary btn-lg">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/coupons/create.blade.php ENDPATH**/ ?>