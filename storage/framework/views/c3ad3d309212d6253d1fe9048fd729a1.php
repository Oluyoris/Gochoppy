
<?php $__env->startSection('title', 'Edit Customer'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center gap-3 mb-4">
    <a href="<?php echo e(route('admin.users.index')); ?>"
       style="width:36px;height:36px;background:#fff;border:1px solid var(--border);border-radius:9px;display:flex;align-items:center;justify-content:center;color:var(--muted);text-decoration:none;flex-shrink:0;transition:all .18s;"
       onmouseover="this.style.borderColor='var(--teal)';this.style.color='var(--teal)'"
       onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--muted)'">
        <i class="fas fa-arrow-left" style="font-size:13px;"></i>
    </a>
    <div>
        <h2 class="gc-page-title mb-0">Edit Customer</h2>
        <p class="gc-page-sub mb-0">Editing: <span style="color:var(--teal);font-weight:700;"><?php echo e($user->name); ?></span></p>
    </div>
</div>


<div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;max-width:860px;">

    
    <div style="padding:18px 24px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
        <div style="width:34px;height:34px;background:var(--teal-soft);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-user-pen" style="color:var(--teal);font-size:14px;"></i>
        </div>
        <span style="font-weight:700;font-size:15px;color:var(--text);">Customer Information</span>
    </div>

    <div style="padding:28px 28px 24px;">

        <form action="<?php echo e(route('admin.users.update', $user)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="row g-4">

                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-user me-1" style="color:var(--teal);font-size:11px;"></i>
                        Full Name
                    </label>
                    <input type="text" name="name"
                           class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('name', $user->name)); ?>"
                           placeholder="Enter full name"
                           required>
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-envelope me-1" style="color:var(--teal);font-size:11px;"></i>
                        Email Address
                    </label>
                    <input type="email" name="email"
                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('email', $user->email)); ?>"
                           placeholder="email@example.com"
                           required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-phone me-1" style="color:var(--teal);font-size:11px;"></i>
                        Phone Number
                    </label>
                    <input type="text" name="phone"
                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('phone', $user->phone)); ?>"
                           placeholder="+234 000 000 0000">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="fas fa-map-pin me-1" style="color:var(--teal);font-size:11px;"></i>
                        State
                    </label>
                    <input type="text" name="state"
                           class="form-control <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           value="<?php echo e(old('state', $user->state)); ?>"
                           placeholder="e.g. Lagos">
                    <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-12">
                    <label class="form-label">
                        <i class="fas fa-location-dot me-1" style="color:var(--teal);font-size:11px;"></i>
                        Address
                    </label>
                    <textarea name="address" rows="3"
                              class="form-control <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              placeholder="Enter delivery address"><?php echo e(old('address', $user->address)); ?></textarea>
                    <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="col-md-6">
                    <label for="popular_bus_stop_id" class="form-label">
                        <i class="fas fa-map-marker-alt me-1" style="color:var(--teal);font-size:11px;"></i>
                        Popular Bus Stop (Default Pickup/Drop-off)
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
                                    <?php echo e(old('popular_bus_stop_id', $user->popular_bus_stop_id ?? '') == $stop->id ? 'selected' : ''); ?>>
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
                    <small class="text-muted">This will be used as the customer's preferred pickup/drop-off location.</small>
                </div>

                
                <div class="col-12">
                    <div style="background:var(--bg);border:1.5px solid var(--border);border-radius:11px;padding:14px 18px;display:flex;align-items:center;gap:14px;">
                        <div style="position:relative;">
                            <input type="checkbox" name="is_active" value="1"
                                   class="form-check-input" id="is_active"
                                   style="width:20px;height:20px;border-radius:5px;border-color:var(--teal);cursor:pointer;"
                                   <?php echo e(old('is_active', $user->is_active) ? 'checked' : ''); ?>>
                        </div>
                        <div>
                            <label class="form-check-label" for="is_active" style="font-weight:700;font-size:14px;color:var(--text);cursor:pointer;margin:0;">
                                Keep account active
                            </label>
                            <p style="font-size:12px;color:var(--muted);margin:2px 0 0;">
                                Uncheck to block this customer from placing orders
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            
            <div style="display:flex;align-items:center;gap:10px;margin-top:28px;padding-top:22px;border-top:1px solid var(--border-light);">
                <button type="submit"
                        style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,var(--teal-dark),var(--teal-light));border:none;color:#fff;border-radius:10px;padding:11px 24px;font-size:14px;font-weight:700;font-family:'Plus Jakarta Sans',sans-serif;cursor:pointer;box-shadow:0 4px 14px rgba(13,148,136,.28);transition:all .2s;">
                    <i class="fas fa-floppy-disk"></i> Save Changes
                </button>
                <a href="<?php echo e(route('admin.users.index')); ?>"
                   style="display:inline-flex;align-items:center;gap:8px;background:#fff;border:1.5px solid var(--border);color:var(--text-body);border-radius:10px;padding:11px 22px;font-size:14px;font-weight:600;text-decoration:none;transition:all .2s;"
                   onmouseover="this.style.borderColor='var(--muted)'"
                   onmouseout="this.style.borderColor='var(--border)'">
                    <i class="fas fa-xmark"></i> Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/users/edit.blade.php ENDPATH**/ ?>