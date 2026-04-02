

<?php $__env->startSection('title', 'All Transactions'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">All Transactions</h2>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.transactions.index')); ?>">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search description or user" 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="">All Types</option>
                            <option value="credit" <?php echo e(request('type') == 'credit' ? 'selected' : ''); ?>>Credit Only</option>
                            <option value="debit" <?php echo e(request('type') == 'debit' ? 'selected' : ''); ?>>Debit Only</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option value="">All Users</option>
                            <option value="customer" <?php echo e(request('role') == 'customer' ? 'selected' : ''); ?>>Customers (Debits)</option>
                            <option value="vendor" <?php echo e(request('role') == 'vendor' ? 'selected' : ''); ?>>Vendors</option>
                            <option value="dispatcher" <?php echo e(request('role') == 'dispatcher' ? 'selected' : ''); ?>>Dispatchers</option>
                            <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="related" class="form-select">
                            <option value="">All Transactions</option>
                            <option value="order" <?php echo e(request('related') == 'order' ? 'selected' : ''); ?>>Only Order Related</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </div>
                    <div class="col-md-1">
                        <a href="<?php echo e(route('admin.transactions.index')); ?>" class="btn btn-secondary w-100">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>User (Role)</th>
                            <th>Description</th>
                            <th>Type</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($tx->created_at->format('d M Y H:i')); ?></td>
                                <td>
                                    <?php echo e($tx->user ? $tx->user->name : 'N/A'); ?>

                                    <small class="text-muted d-block">
                                        (<?php echo e(ucfirst($tx->user?->role ?? 'Unknown')); ?>)
                                    </small>
                                </td>
                                <td><?php echo e($tx->description); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($tx->type == 'credit' ? 'success' : 'danger'); ?>">
                                        <?php echo e(ucfirst($tx->type)); ?>

                                    </span>
                                </td>
                                <td class="fw-bold <?php echo e($tx->type == 'debit' ? 'text-danger' : 'text-success'); ?>">
                                    <?php echo e($tx->type == 'debit' ? '-' : '+'); ?> ₦<?php echo e(number_format($tx->amount, 2)); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">No transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            <?php echo e($transactions->appends(request()->query())->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/transactions/index.blade.php ENDPATH**/ ?>