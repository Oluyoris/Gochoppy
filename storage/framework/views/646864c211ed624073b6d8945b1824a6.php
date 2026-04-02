
<?php $__env->startSection('title', 'Orders'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">All Orders</h2>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Order #</th>
                            <th>Customer</th>
                            <th>Vendor</th>
                            <th>Dispatcher</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Placed</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($order->order_number); ?></td>
                                <td><?php echo e($order->customer->name ?? 'N/A'); ?></td>
                                <td><?php echo e($order->vendor->vendorProfile->company_name ?? $order->vendor->email ?? 'N/A'); ?></td>
                                <td><?php echo e($order->dispatcher ? $order->dispatcher->name : 'Not assigned'); ?></td>
                                <td>₦<?php echo e(number_format($order->grand_total, 2)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $order->status))); ?>

                                    </span>
                                </td>
                                <td><?php echo e(ucfirst($order->payment_method)); ?></td>
                                <td><?php echo e($order->created_at->diffForHumans()); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5">No orders yet</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
       
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>