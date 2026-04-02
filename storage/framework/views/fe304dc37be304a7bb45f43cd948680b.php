

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Customer Deposits (Pending)</h1>
        <a href="<?php echo e(route('admin.deposits.index')); ?>" class="btn btn-secondary">Refresh</a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Proof</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $deposits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $deposit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($deposit->reference); ?></strong></td>
                        <td>
                            <?php echo e($deposit->user->name); ?> <br>
                            <small class="text-muted"><?php echo e($deposit->user->phone); ?></small>
                        </td>
                        <td><strong>₦<?php echo e(number_format($deposit->amount, 2)); ?></strong></td>
                        <td>
                            <?php if($deposit->proof): ?>
                                <span class="badge bg-info">Proof Uploaded</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">No Proof</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($deposit->created_at->format('d M, Y H:i')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.deposits.show', $deposit)); ?>" 
                               class="btn btn-primary btn-sm mb-1">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                            
                            <form action="<?php echo e(route('admin.deposits.approve', $deposit)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-success btn-sm" 
                                        onclick="return confirm('Approve this deposit and credit wallet?')">
                                    Approve
                                </button>
                            </form>
                            
                            <form action="<?php echo e(route('admin.deposits.reject', $deposit)); ?>" method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-danger btn-sm" 
                                        onclick="return confirm('Reject this deposit?')">
                                    Reject
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center py-4">No pending deposits at the moment.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        <?php echo e($deposits->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/deposits/index.blade.php ENDPATH**/ ?>