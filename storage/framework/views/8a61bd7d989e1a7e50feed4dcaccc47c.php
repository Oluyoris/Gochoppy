
<?php $__env->startSection('title', 'Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h2 class="mb-4">System Settings</h2>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.settings.update')); ?>">
                <?php echo csrf_field(); ?>
                <!-- IMPORTANT: Removed <?php echo method_field('PUT'); ?> – we use POST -->

                <h4 class="mb-3">Pricing & Charges</h4>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="service_charge_amount" class="form-label">Service Charge Amount (₦)</label>
                        <input type="number" name="service_charge_amount" step="0.01" class="form-control" 
                               value="<?php echo e(old('service_charge_amount', $serviceChargeAmount)); ?>" required min="0">
                        <small class="form-text text-muted">Fixed amount added to every order</small>
                    </div>

                    

                <hr>

                <h4 class="mb-3">Payment Gateways</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="paystack_enabled" value="1" 
                                   class="form-check-input" id="paystack_enabled" 
                                   <?php echo e($paystackEnabled ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="paystack_enabled">Enable Paystack</label>
                        </div>

                        <input type="text" name="paystack_public_key" class="form-control mb-2" 
                               placeholder="Public Key" value="<?php echo e(old('paystack_public_key', $paystackPublicKey)); ?>">

                        <input type="text" name="paystack_secret_key" class="form-control" 
                               placeholder="Secret Key" value="<?php echo e(old('paystack_secret_key', $paystackSecretKey)); ?>">
                    </div>

                    <div class="col-md-6">
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" name="manual_bank_enabled" value="1" 
                                   class="form-check-input" id="manual_bank_enabled" 
                                   <?php echo e($manualBankEnabled ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="manual_bank_enabled">Enable Manual Bank Transfer</label>
                        </div>

                        <input type="text" name="manual_bank_name" class="form-control mb-2" 
                               placeholder="Bank Name" value="<?php echo e(old('manual_bank_name', $manualBankName)); ?>">

                        <input type="text" name="manual_account_number" class="form-control mb-2" 
                               placeholder="Account Number" value="<?php echo e(old('manual_account_number', $manualAccountNumber)); ?>">

                        <input type="text" name="manual_account_name" class="form-control" 
                               placeholder="Account Name" value="<?php echo e(old('manual_account_name', $manualAccountName)); ?>">
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Save All Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/settings/index.blade.php ENDPATH**/ ?>