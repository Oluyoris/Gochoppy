  

<?php $__env->startSection('title', 'Manage Coupons'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Coupons Management</h1>
        <a href="<?php echo e(route('admin.coupons.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Coupon
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Discount</th>
                        <th>Categories</th>
                        <th>Uses</th>
                        <th>Expires</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><strong><?php echo e($coupon->code); ?></strong></td>
                            <td><?php echo e($coupon->title); ?></td>
                            <td>₦<?php echo e(number_format($coupon->discount_amount, 2)); ?></td>
                            <td>
                                <?php $__currentLoopData = $coupon->applicable_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-info me-1"><?php echo e(ucfirst($cat)); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td>
                                <?php echo e($coupon->used_count); ?> / <?php echo e($coupon->max_uses); ?>

                            </td>
                            <td>
                                <?php if($coupon->expires_at): ?>
                                    <?php echo e($coupon->expires_at->format('d M, Y')); ?>

                                <?php else: ?>
                                    <span class="text-success">Never</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($coupon->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.coupons.edit', $coupon)); ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?php echo e(route('admin.coupons.toggle-status', $coupon)); ?>" 
                                   class="btn btn-sm <?php echo e($coupon->is_active ? 'btn-secondary' : 'btn-success'); ?>">
                                    <?php echo e($coupon->is_active ? 'Deactivate' : 'Activate'); ?>

                                </a>
                                <form action="<?php echo e(route('admin.coupons.destroy', $coupon)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this coupon?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">No coupons found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <?php echo e($coupons->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/coupons/index.blade.php ENDPATH**/ ?>