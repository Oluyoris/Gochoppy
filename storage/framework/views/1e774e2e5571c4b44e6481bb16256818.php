

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="mb-4">
        <a href="<?php echo e(route('admin.deposits.index')); ?>" class="btn btn-secondary">
            ← Back to Deposits
        </a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Deposit Details - <?php echo e($deposit->reference); ?></h4>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <h5 class="text-muted mb-3">Customer Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Customer Name</th>
                            <td><?php echo e($deposit->user->name); ?></td>
                        </tr>
                        <tr>
                            <th>Phone Number</th>
                            <td><?php echo e($deposit->user->phone); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo e($deposit->user->email ?? 'N/A'); ?></td>
                        </tr>
                    </table>

                    <h5 class="text-muted mt-4 mb-3">Deposit Information</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Reference</th>
                            <td><strong><?php echo e($deposit->reference); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <td><strong class="text-success">₦<?php echo e(number_format($deposit->amount, 2)); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Payment Method</th>
                            <td><?php echo e(ucfirst(str_replace('_', ' ', $deposit->payment_method))); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if($deposit->status === 'pending'): ?>
                                    <span class="badge bg-warning">Pending Approval</span>
                                <?php elseif($deposit->status === 'approved'): ?>
                                    <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Rejected</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Submitted On</th>
                            <td><?php echo e($deposit->created_at->format('d M, Y \a\t H:i')); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Right Column - Proof -->
                <div class="col-md-6">
                    <h5 class="text-muted mb-3">Payment Proof</h5>
                    <?php if($deposit->proof): ?>
                        <div class="border p-3 text-center bg-light">
                            <img src="<?php echo e(Storage::url($deposit->proof)); ?>" 
                                 alt="Payment Proof" 
                                 class="img-fluid" 
                                 style="max-height: 450px; border: 2px solid #ddd;">
                            <p class="mt-3">
                                <a href="<?php echo e(Storage::url($deposit->proof)); ?>" 
                                   target="_blank" 
                                   class="btn btn-info">
                                    <i class="fas fa-download"></i> Open Full Image
                                </a>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning">
                            No proof image was uploaded for this deposit.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card-footer">
            <?php if($deposit->status === 'pending'): ?>
                <form action="<?php echo e(route('admin.deposits.approve', $deposit)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-success btn-lg px-5" 
                            onclick="return confirm('Are you sure you want to APPROVE this deposit and credit the customer wallet?')">
                        ✅ Approve & Credit Wallet
                    </button>
                </form>

                <form action="<?php echo e(route('admin.deposits.reject', $deposit)); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger btn-lg px-5" 
                            onclick="return confirm('Are you sure you want to REJECT this deposit?')">
                        ❌ Reject Deposit
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-info">
                    This deposit has already been <?php echo e($deposit->status); ?>.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/deposits/show.blade.php ENDPATH**/ ?>