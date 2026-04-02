
<?php $__env->startSection('title', 'Items'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Items Management</h2>
        <a href="<?php echo e(route('admin.items.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Item
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <!-- Optional search/filter -->
            <form method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name or category..." 
                               value="<?php echo e(request('search')); ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="vendor_type" class="form-select">
                            <option value="">All Types</option>
                            <option value="kitchen" <?php echo e(request('vendor_type') == 'kitchen' ? 'selected' : ''); ?>>Kitchen</option>
                            <option value="supermarket" <?php echo e(request('vendor_type') == 'supermarket' ? 'selected' : ''); ?>>Supermarket</option>
                            <option value="pharmacy" <?php echo e(request('vendor_type') == 'pharmacy' ? 'selected' : ''); ?>>Pharmacy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Type</th>
                            <th>Vendor</th>
                            <th>Available</th>
                            <th width="180" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <?php if($item->image): ?>
                                        <img src="<?php echo e(Storage::url($item->image)); ?>" alt="<?php echo e($item->name); ?>" 
                                             style="width:60px; height:60px; object-fit:cover; border-radius:4px;">
                                    <?php else: ?>
                                        —
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->name); ?></td>
                                <td><?php echo e($item->category ?? '—'); ?></td>
                                <td>₦<?php echo e(number_format($item->price, 2)); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($item->vendor_type == 'kitchen' ? 'warning' : ($item->vendor_type == 'pharmacy' ? 'danger' : 'info')); ?>">
                                        <?php echo e(ucfirst($item->vendor_type)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($item->vendor->name ?? 'N/A'); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($item->is_available ? 'success' : 'danger'); ?>">
                                        <?php echo e($item->is_available ? 'Yes' : 'No'); ?>

                                    </span>
                                </td>
                                <td class="text-end">
                                    <a href="<?php echo e(route('admin.items.edit', $item)); ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <form action="<?php echo e(route('admin.items.destroy', $item)); ?>" method="POST" class="d-inline"
                                          onsubmit="return confirm('Delete this item? This cannot be undone.')">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">No items found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                <?php echo e($items->appends(request()->query())->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/items/index.blade.php ENDPATH**/ ?>