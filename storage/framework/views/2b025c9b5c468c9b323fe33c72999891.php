

<?php $__env->startSection('title', 'Popular Bus Stops - Interval Control'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Popular Bus Stops & Delivery Intervals</h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBusStopModal">
            + Add New Bus Stop
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <!-- Bus Stops List -->
    <div class="card mb-4">
        <div class="card-header">
            <h5>Bus Stops List (Click any to edit intervals)</h5>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bus Stop Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $busStops; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($loop->iteration); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.bus-stops.show', $stop)); ?>" class="text-primary fw-bold">
                                    <?php echo e($stop->name); ?>

                                </a>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.bus-stops.show', $stop)); ?>" class="btn btn-sm btn-warning">
                                    Edit Intervals
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3" class="text-center py-4">No bus stops added yet.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delivery Fee Split Settings -->
    <div class="card">
        <div class="card-header">
            <h5>Delivery Fee Split (Dispatch vs Admin)</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.delivery-settings.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="row g-3">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Dispatch Rider Percentage (%)</label>
                        <input type="number" 
                               name="dispatch_percentage" 
                               class="form-control form-control-lg" 
                               value="<?php echo e($settings->dispatch_percentage ?? 60); ?>" 
                               min="0" 
                               max="100" 
                               required>
                        <small class="text-muted">Percentage of delivery fee that goes to dispatcher</small>
                    </div>

                    <div class="col-md-5">
                        <label class="form-label fw-bold">Admin Percentage (%)</label>
                        <input type="text" 
                               class="form-control form-control-lg bg-light" 
                               value="<?php echo e($settings->admin_percentage ?? 40); ?>" 
                               readonly>
                        <small class="text-muted">Admin automatically gets the remaining + 100% service charge</small>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-save"></i> Save Split
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>


<div class="modal fade" id="addBusStopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo e(route('admin.bus-stops.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Add New Popular Bus Stop</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bus Stop Name (e.g. IRONA, OSHODI)</label>
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               required 
                               placeholder="Enter bus stop name" 
                               style="text-transform: uppercase;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Bus Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/bus-stops/index.blade.php ENDPATH**/ ?>