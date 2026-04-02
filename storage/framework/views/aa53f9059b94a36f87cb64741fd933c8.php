

<?php $__env->startSection('title', 'Edit Intervals - ' . $busStop->name); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between mb-4">
        <h1>Delivery Intervals From: <strong><?php echo e($busStop->name); ?></strong></h1>
        <a href="<?php echo e(route('admin.bus-stops.index')); ?>" class="btn btn-secondary">← Back to List</a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.bus-stops.update', $busStop)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="card">
            <div class="card-header">
                <h5>Set Price & Estimated Time to Other Bus Stops</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>To Bus Stop</th>
                            <th>Price (₦)</th>
                            <th>Estimated Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $intervals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($interval->toStop->name); ?></strong></td>
                                <td>
                                    <input type="number" 
                                           name="intervals[<?php echo e($interval->id); ?>][price]" 
                                           value="<?php echo e($interval->price); ?>" 
                                           class="form-control" min="500" required>
                                </td>
                                <td>
                                    <input type="text" 
                                           name="intervals[<?php echo e($interval->id); ?>][estimated_time]" 
                                           value="<?php echo e($interval->estimated_time); ?>" 
                                           class="form-control" required>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-success">Save All Changes</button>
            </div>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/bus-stops/show.blade.php ENDPATH**/ ?>