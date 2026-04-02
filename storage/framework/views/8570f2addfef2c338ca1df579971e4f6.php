
<?php $__env->startSection('title', 'Vendor Subscriptions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Vendor Subscriptions</h2>
        <a href="<?php echo e(route('admin.subscriptions.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Add New Plan
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Vendor</th>
                            <th>Plan Type</th>
                            <th>Amount</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Active</th>
                            <th width="180" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($sub->vendor->vendorProfile->company_name ?? $sub->vendor->email); ?></td>
                                <td><?php echo e(ucfirst($sub->plan_type)); ?></td>
                                <td>₦<?php echo e(number_format($sub->amount, 2)); ?></td>
                                <td><?php echo e($sub->start_date->format('d M Y')); ?></td>
                                <td><?php echo e($sub->end_date->format('d M Y')); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($sub->is_active ? 'success' : 'danger'); ?>">
                                        <?php echo e($sub->is_active ? 'Active' : 'Inactive'); ?>

                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.subscriptions.edit', $sub)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="<?php echo e(route('admin.subscriptions.destroy', $sub)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this subscription plan?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">No subscriptions yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <?php echo e($subscriptions->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/subscriptions/index.blade.php ENDPATH**/ ?>