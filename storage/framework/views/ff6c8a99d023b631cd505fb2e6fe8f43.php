
<?php $__env->startSection('title', 'Edit Vendor'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo e(route('admin.vendors.index')); ?>"
       style="width:36px;height:36px;background:#fff;border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;flex-shrink:0;transition:all .18s;"
       onmouseover="this.style.borderColor='var(--teal)';this.style.color='var(--teal)'"
       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left" style="font-size:13px;"></i>
    </a>
    <div>
        <h2 class="gc-page-title mb-0">Edit Vendor</h2>
        <p class="gc-page-sub mb-0">Editing: <span style="color:var(--teal);font-weight:700;"><?php echo e($vendor->vendorProfile->company_name ?? 'Unnamed Vendor'); ?></span></p>
    </div>
</div>

<form method="POST" action="<?php echo e(route('admin.vendors.update', $vendor)); ?>" enctype="multipart/form-data">
<?php echo csrf_field(); ?>
<?php echo method_field('PUT'); ?>


<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:var(--teal-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-building" style="color:var(--teal);font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Business Information</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">

            <div class="col-md-6">
                <label for="company_name" class="form-label"><i class="fas fa-building me-1" style="color:var(--teal);font-size:11px;"></i>Company Name</label>
                <input type="text" name="company_name" id="company_name"
                       class="form-control <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('company_name', $vendor->vendorProfile->company_name ?? '')); ?>" required>
                <?php $__errorArgs = ['company_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label for="type" class="form-label"><i class="fas fa-tag me-1" style="color:var(--teal);font-size:11px;"></i>Vendor Type</label>
                <select name="type" id="type" class="form-select <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                    <option value="">Select Type</option>
                    <option value="kitchen"     <?php echo e(old('type', $vendor->vendorProfile->type ?? '') == 'kitchen'     ? 'selected' : ''); ?>>🍳 Kitchen</option>
                    <option value="supermarket" <?php echo e(old('type', $vendor->vendorProfile->type ?? '') == 'supermarket' ? 'selected' : ''); ?>>🛒 Supermarket</option>
                    <option value="pharmacy"    <?php echo e(old('type', $vendor->vendorProfile->type ?? '') == 'pharmacy'    ? 'selected' : ''); ?>>💊 Pharmacy</option>
                </select>
                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-12">
                <label for="logo" class="form-label"><i class="fas fa-image me-1" style="color:var(--teal);font-size:11px;"></i>Company Logo <span style="color:var(--muted);font-weight:400;">(PNG/JPG, max 2MB)</span></label>

                <?php if($vendor->vendorProfile->logo): ?>
                    <div style="display:flex;align-items:center;gap:14px;background:var(--bg);border:1px solid var(--border);border-radius:10px;padding:12px 16px;margin-bottom:12px;">
                        <img src="<?php echo e(asset('storage/' . $vendor->vendorProfile->logo)); ?>"
                             alt="Current Logo"
                             style="width:56px;height:56px;border-radius:10px;object-fit:cover;border:1px solid var(--border);">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:var(--text);margin:0 0 2px;">Current Logo</p>
                            <p style="font-size:12px;color:var(--muted);margin:0;">Upload a new file below to replace it</p>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="file" name="logo" id="logo"
                       class="form-control <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       accept="image/png,image/jpeg">
                <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small style="color:var(--muted);font-size:12px;">Leave blank to keep current logo. Recommended: 200×200 pixels</small>
            </div>

            <div class="col-12">
                <label for="address" class="form-label"><i class="fas fa-location-dot me-1" style="color:var(--teal);font-size:11px;"></i>Address</label>
                <textarea name="address" id="address" rows="3"
                          class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required><?php echo e(old('address', $vendor->address ?? '')); ?></textarea>
                <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            
            <div class="col-md-6">
                <label for="popular_bus_stop_id" class="form-label">
                    <i class="fas fa-map-marker-alt me-1" style="color:var(--teal);font-size:11px;"></i>
                    Popular Bus Stop (Pickup Location)
                </label>
                <select name="popular_bus_stop_id" id="popular_bus_stop_id" 
                        class="form-select <?php $__errorArgs = ['popular_bus_stop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Select Bus Stop</option>
                    <?php $__currentLoopData = \App\Models\PopularBusStop::orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($stop->id); ?>" 
                                <?php echo e(old('popular_bus_stop_id', $vendor->vendorProfile?->popular_bus_stop_id ?? '') == $stop->id ? 'selected' : ''); ?>>
                            <?php echo e($stop->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['popular_bus_stop_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
                    <div class="invalid-feedback"><?php echo e($message); ?></div> 
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <small class="text-muted">This is where dispatch riders will pick up orders from this vendor.</small>
            </div>

        </div>
    </div>
</div>


<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#dbeafe;border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-id-card" style="color:#2563eb;font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Contact Details</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">

            <div class="col-md-6">
                <label for="email" class="form-label"><i class="fas fa-envelope me-1" style="color:var(--teal);font-size:11px;"></i>Email Address</label>
                <input type="email" name="email" id="email"
                       class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('email', $vendor->email ?? '')); ?>" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-6">
                <label for="phone" class="form-label"><i class="fas fa-phone me-1" style="color:var(--teal);font-size:11px;"></i>Phone Number</label>
                <input type="text" name="phone" id="phone"
                       class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('phone', $vendor->phone ?? '')); ?>" required>
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

        </div>
    </div>
</div>


<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:20px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:var(--orange-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-building-columns" style="color:var(--orange);font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Bank Details <span style="font-size:12px;font-weight:500;color:var(--muted);">(for payouts)</span></span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">

            <div class="col-md-4">
                <label for="bank_name" class="form-label"><i class="fas fa-landmark me-1" style="color:var(--teal);font-size:11px;"></i>Bank Name</label>
                <input type="text" name="bank_name" id="bank_name"
                       class="form-control <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('bank_name', $vendor->vendorProfile->bank_name ?? '')); ?>" required>
                <?php $__errorArgs = ['bank_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label for="account_number" class="form-label"><i class="fas fa-hashtag me-1" style="color:var(--teal);font-size:11px;"></i>Account Number</label>
                <input type="text" name="account_number" id="account_number"
                       class="form-control <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('account_number', $vendor->vendorProfile->account_number ?? '')); ?>" required>
                <?php $__errorArgs = ['account_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="col-md-4">
                <label for="account_name" class="form-label"><i class="fas fa-user me-1" style="color:var(--teal);font-size:11px;"></i>Account Name</label>
                <input type="text" name="account_name" id="account_name"
                       class="form-control <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('account_name', $vendor->vendorProfile->account_name ?? '')); ?>" required>
                <?php $__errorArgs = ['account_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

        </div>
    </div>
</div>


<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;margin-bottom:24px;">
    <div style="padding:16px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:32px;height:32px;background:#f3e8ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-sliders" style="color:#7e22ce;font-size:13px;"></i>
        </div>
        <span style="font-weight:700;font-size:14.5px;color:var(--text);">Status Controls</span>
    </div>
    <div style="padding:24px;">
        <div class="row g-4">

            <div class="col-md-6">
                <div style="background:var(--bg);border:1.5px solid var(--border);border-radius:11px;padding:16px 18px;display:flex;align-items:center;gap:14px;">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           id="is_active"
                           style="width:20px;height:20px;border-radius:5px;border-color:var(--teal);cursor:pointer;flex-shrink:0;"
                           <?php echo e(old('is_active', $vendor->is_active) ? 'checked' : ''); ?>>
                    <div>
                        <label class="form-check-label" for="is_active" style="font-weight:700;font-size:14px;color:var(--text);cursor:pointer;margin:0;">Active Status</label>
                        <p style="font-size:12px;color:var(--muted);margin:2px 0 0;">Uncheck to block this vendor from receiving orders</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div style="background:var(--bg);border:1.5px solid var(--border);border-radius:11px;padding:16px 18px;display:flex;align-items:center;gap:14px;">
                    <input class="form-check-input" type="checkbox" name="is_verified" value="1"
                           id="is_verified"
                           style="width:20px;height:20px;border-radius:5px;border-color:var(--teal);cursor:pointer;flex-shrink:0;"
                           <?php echo e(old('is_verified', $vendor->vendorProfile->is_verified ?? false) ? 'checked' : ''); ?>>
                    <div>
                        <label class="form-check-label" for="is_verified" style="font-weight:700;font-size:14px;color:var(--text);cursor:pointer;margin:0;">Verified Status</label>
                        <p style="font-size:12px;color:var(--muted);margin:2px 0 0;">Mark this vendor as verified on the platform</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


<div style="display:flex;align-items:center;gap:10px;">
    <button type="submit"
            style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--teal-dark),var(--teal-light));border:none;color:#fff;border-radius:10px;padding:12px 28px;font-size:14.5px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;box-shadow:0 4px 16px rgba(13,148,136,.3);transition:all .2s;">
        <i class="fas fa-floppy-disk"></i> Update Vendor
    </button>
    <a href="<?php echo e(route('admin.vendors.index')); ?>"
       style="display:inline-flex;align-items:center;gap:8px;background:#fff;border:1.5px solid var(--border);color:var(--text-body);border-radius:10px;padding:12px 22px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;">
        <i class="fas fa-xmark"></i> Cancel
    </a>
</div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/vendors/edit.blade.php ENDPATH**/ ?>