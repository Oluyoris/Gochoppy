
<?php $__env->startSection('title', 'Dispatchers'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Dispatchers</h2>
        <a href="<?php echo e(route('admin.dispatchers.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Onboard New Dispatcher
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Plate Number</th>
                            <th>NIN</th>
                            <th>Active</th>
                            <th width="220" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $dispatchers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dispatcher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $profile = $dispatcher->dispatcherProfile;
                            ?>

                            <tr>
                                <td>
                                    <?php if($profile && $profile->avatar): ?>
                                        <img src="<?php echo e(asset('storage/' . $profile->avatar)); ?>" 
                                             alt="Avatar" 
                                             class="rounded-circle" 
                                             width="50" height="50" 
                                             style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                            <i class="fas fa-motorcycle text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td class="fw-medium"><?php echo e($profile?->full_name ?? $dispatcher->name ?? '—'); ?></td>
                                <td><?php echo e($dispatcher->email); ?></td>
                                <td><?php echo e($dispatcher->phone); ?></td>
                                <td><?php echo e($profile?->plate_number ?? '—'); ?></td>
                                <td><?php echo e($profile?->nin_number ?? '—'); ?></td>

                                <td>
                                    <?php if($dispatcher->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Blocked</span>
                                    <?php endif; ?>
                                </td>

                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('admin.dispatchers.edit', $dispatcher)); ?>" class="btn btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="<?php echo e(route('admin.dispatchers.destroy', $dispatcher)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger" title="Delete" onclick="return confirm('Delete this dispatcher?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-motorcycle fa-3x mb-3 d-block text-muted"></i>
                                    No dispatchers onboarded yet
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/dispatchers/index.blade.php ENDPATH**/ ?>