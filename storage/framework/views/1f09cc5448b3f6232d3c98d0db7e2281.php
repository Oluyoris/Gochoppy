
<?php $__env->startSection('title', 'Withdrawals'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Withdrawal Requests</h2>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Bank Details</th>
                            <th>Status</th>
                            <th>Requested</th>
                            <th>Processed</th>
                            <th width="220" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($wd->id); ?></td>
                                <td><?php echo e($wd->wallet->user->name ?? 'N/A'); ?></td>
                                <td>₦<?php echo e(number_format($wd->amount, 2)); ?></td>
                                <td>
                                    <?php echo e($wd->bank_name); ?><br>
                                    <?php echo e($wd->account_number); ?><br>
                                    <?php echo e($wd->account_name); ?>

                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($wd->status == 'pending' ? 'warning' : ($wd->status == 'completed' ? 'success' : 'danger')); ?>">
                                        <?php echo e(ucfirst($wd->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($wd->created_at->diffForHumans()); ?></td>
                                <td><?php echo e($wd->processed_at ? $wd->processed_at->diffForHumans() : '—'); ?></td>
                                <td class="text-end">
                                    <?php if($wd->status == 'pending'): ?>
                                        <form action="<?php echo e(route('admin.withdrawals.approve', $wd)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this withdrawal?')">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>

                                        <form action="<?php echo e(route('admin.withdrawals.reject', $wd)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Reject this withdrawal?')">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">No withdrawal requests yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <?php echo e($withdrawals->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/withdrawals/index.blade.php ENDPATH**/ ?>