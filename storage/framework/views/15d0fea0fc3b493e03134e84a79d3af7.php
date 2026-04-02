
<?php $__env->startSection('title', 'Order Details #' . $order->order_number); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">Order Details #<?php echo e($order->order_number); ?></h2>

    <div class="card shadow">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Customer</h5>
                    <p><?php echo e($order->customer->name); ?> (<?php echo e($order->customer->email); ?>)</p>
                    <p>Phone: <?php echo e($order->phone); ?></p>
                    <p>Address: <?php echo e($order->customer_address); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>Vendor</h5>
                    <p><?php echo e($order->vendor->vendorProfile->company_name ?? $order->vendor->name); ?></p>
                    <p>Address: <?php echo e($order->vendor_address); ?></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Dispatcher</h5>
                    <p><?php echo e($order->dispatcher ? $order->dispatcher->name : 'Not assigned'); ?></p>
                </div>
                <div class="col-md-6">
                    <h5>Delivery Code</h5>
                    <p><?php echo e($order->delivery_code); ?></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Status</h5>
                    <span class="badge bg-primary"><?php echo e(ucfirst($order->status)); ?></span>
                </div>
                <div class="col-md-6">
                    <h5>Payment Method</h5>
                    <p><?php echo e(ucfirst($order->payment_method)); ?></p>
                    <p>Payment Status: <?php echo e(ucfirst($order->payment_status)); ?></p>
                </div>
            </div>

            <!-- Payment Approval/Rejection for Bank Transfer -->
            <?php if($order->payment_method === 'bank_transfer' && $order->payment_status === 'pending'): ?>
                <div class="alert alert-warning mb-4">
                    <strong>Payment Pending Verification</strong><br>
                    Admin: Please review the payment proof below and approve or reject.
                </div>

                <div class="mb-4">
                    <!-- Approve Form -->
                    <form action="<?php echo e(route('admin.orders.verify-payment', $order)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="action" value="approve">
                        <button type="submit" class="btn btn-success btn-lg me-2">
                            <i class="fas fa-check"></i> Approve Payment
                        </button>
                    </form>

                    <!-- Reject Form -->
                    <form action="<?php echo e(route('admin.orders.verify-payment', $order)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn btn-danger btn-lg">
                            <i class="fas fa-times"></i> Reject Payment
                        </button>
                    </form>
                </div>
            <?php elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'success'): ?>
                <div class="alert alert-success mb-4">
                    Payment has been approved.
                </div>
            <?php elseif($order->payment_method === 'bank_transfer' && $order->payment_status === 'failed'): ?>
                <div class="alert alert-danger mb-4">
                    Payment was rejected / failed.
                </div>
            <?php endif; ?>

            <?php if($order->payment_method === 'bank_transfer' && $order->payment_proof): ?>
                <h5>Payment Proof</h5>
                <img src="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" alt="Payment Proof" class="img-fluid rounded mb-3" style="max-width: 600px;">
                <br>
                <a href="<?php echo e(asset('storage/' . $order->payment_proof)); ?>" class="btn btn-primary" download>Download Proof</a>
            <?php elseif($order->payment_method === 'paystack'): ?>
                <p class="text-muted">Paystack payment – automatic verification via callback.</p>
            <?php endif; ?>

            <h5 class="mt-4">Order Items</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($item->item->name); ?></td>
                            <td><?php echo e($item->quantity); ?></td>
                            <td>₦<?php echo e(number_format($item->price, 2)); ?></td>
                            <td>₦<?php echo e(number_format($item->subtotal, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center">No items</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Financial Breakdown</h5>
                    <p>Items Total: ₦<?php echo e(number_format($order->items_total, 2)); ?></p>
                    <p>Delivery Fee: ₦<?php echo e(number_format($order->delivery_fee, 2)); ?></p>
                    <p>Service Charge: ₦<?php echo e(number_format($order->service_charge, 2)); ?></p>
                    <p><strong>Grand Total: ₦<?php echo e(number_format($order->grand_total, 2)); ?></strong></p>
                </div>
                <div class="col-md-6">
                    <h5>Notes</h5>
                    <p><?php echo e($order->notes ?? 'No notes'); ?></p>
                </div>
            </div>

            <h5 class="mt-4">Update Order Status</h5>
            <form action="<?php echo e(route('admin.orders.update-status', $order)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                <div class="input-group mb-3">
                    <select name="status" class="form-select">
                        <option value="pending_payment" <?php echo e($order->status === 'pending_payment' ? 'selected' : ''); ?>>Pending Payment</option>
                        <option value="paid" <?php echo e($order->status === 'paid' ? 'selected' : ''); ?>>Paid</option>
                        <option value="preparing" <?php echo e($order->status === 'preparing' ? 'selected' : ''); ?>>Preparing</option>
                        <option value="ready_for_pickup" <?php echo e($order->status === 'ready_for_pickup' ? 'selected' : ''); ?>>Ready for Pickup</option>
                        <option value="picked_up" <?php echo e($order->status === 'picked_up' ? 'selected' : ''); ?>>Picked Up</option>
                        <option value="in_transit" <?php echo e($order->status === 'in_transit' ? 'selected' : ''); ?>>In Transit</option>
                        <option value="delivered" <?php echo e($order->status === 'delivered' ? 'selected' : ''); ?>>Delivered</option>
                        <option value="cancelled" <?php echo e($order->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>