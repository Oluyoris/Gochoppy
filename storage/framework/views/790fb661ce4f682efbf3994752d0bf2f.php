
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;letter-spacing:-.4px;margin-bottom:3px;">
            Good <?php echo e(date('H') < 12 ? 'Morning' : (date('H') < 17 ? 'Afternoon' : 'Evening')); ?> 👋
        </h2>
        <p style="color:var(--muted);font-size:13px;margin:0;">
            <i class="fas fa-calendar-day me-1" style="color:var(--teal);"></i>
            <?php echo e(now()->format('l, d F Y')); ?> &mdash; Here's your platform overview
        </p>
    </div>

    <div class="d-flex gap-2">
        
        <button onclick="refreshDashboard()" 
                style="background:var(--teal-soft);border:1px solid #99f6e4;border-radius:10px;padding:8px 16px;font-size:12.5px;font-weight:600;color:var(--teal-dark);display:flex;align-items:center;gap:6px;">
            <i class="fas fa-sync-alt"></i>
            Refresh Dashboard
        </button>

        <div style="background:var(--teal-soft);border:1px solid #99f6e4;border-radius:10px;padding:8px 16px;font-size:12.5px;font-weight:600;color:var(--teal-dark);">
            <i class="fas fa-circle me-1" style="font-size:7px;color:var(--teal);vertical-align:middle;"></i>
            Live Data
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    
    
    
    
    <div class="col-xl-3 col-md-6">
        <div style="background:linear-gradient(135deg,var(--teal-dark) 0%,var(--teal-light) 100%);border:none;border-radius:16px;padding:24px 22px;box-shadow:0 8px 24px rgba(13,148,136,.35);position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;" 
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 36px rgba(13,148,136,.45)'" 
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(13,148,136,.35)'">
            <div style="position:absolute;right:-18px;bottom:-18px;width:100px;height:100px;background:rgba(255,255,255,.1);border-radius:50%;"></div>
            <div style="position:absolute;right:28px;top:-20px;width:60px;height:60px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.75);">Total Customers</span>
                <div style="width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-users" style="color:#fff;font-size:16px;"></i>
                </div>
            </div>
            <div style="font-size:2.4rem;font-weight:800;color:#fff;letter-spacing:-1.5px;line-height:1;"><?php echo e(number_format($totalCustomers)); ?></div>
            <div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:7px;">People who order food</div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div style="background:linear-gradient(135deg,#c2410c 0%,var(--orange-light) 100%);border:none;border-radius:16px;padding:24px 22px;box-shadow:0 8px 24px rgba(249,115,22,.35);position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;" 
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 36px rgba(249,115,22,.45)'" 
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(249,115,22,.35)'">
            <div style="position:absolute;right:-18px;bottom:-18px;width:100px;height:100px;background:rgba(255,255,255,.1);border-radius:50%;"></div>
            <div style="position:absolute;right:28px;top:-20px;width:60px;height:60px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.75);">Total Vendors</span>
                <div style="width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-store" style="color:#fff;font-size:16px;"></i>
                </div>
            </div>
            <div style="font-size:2.4rem;font-weight:800;color:#fff;letter-spacing:-1.5px;line-height:1;"><?php echo e(number_format($totalVendors)); ?></div>
            <div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:7px;">Kitchens, Supermarkets, Pharmacies</div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div style="background:linear-gradient(135deg,#4338ca 0%,#818cf8 100%);border:none;border-radius:16px;padding:24px 22px;box-shadow:0 8px 24px rgba(99,102,241,.35);position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;" 
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 36px rgba(99,102,241,.45)'" 
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(99,102,241,.35)'">
            <div style="position:absolute;right:-18px;bottom:-18px;width:100px;height:100px;background:rgba(255,255,255,.1);border-radius:50%;"></div>
            <div style="position:absolute;right:28px;top:-20px;width:60px;height:60px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.75);">Total Dispatchers</span>
                <div style="width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-motorcycle" style="color:#fff;font-size:16px;"></i>
                </div>
            </div>
            <div style="font-size:2.4rem;font-weight:800;color:#fff;letter-spacing:-1.5px;line-height:1;"><?php echo e(number_format($totalDispatchers)); ?></div>
            <div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:7px;">Delivery riders</div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div style="background:linear-gradient(135deg,#065f46 0%,#34d399 100%);border:none;border-radius:16px;padding:24px 22px;box-shadow:0 8px 24px rgba(16,185,129,.35);position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;" 
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 36px rgba(16,185,129,.45)'" 
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(16,185,129,.35)'">
            <div style="position:absolute;right:-18px;bottom:-18px;width:100px;height:100px;background:rgba(255,255,255,.1);border-radius:50%;"></div>
            <div style="position:absolute;right:28px;top:-20px;width:60px;height:60px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.75);">Deliveries Today</span>
                <div style="width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-truck-fast" style="color:#fff;font-size:16px;"></i>
                </div>
            </div>
            <div style="font-size:2.4rem;font-weight:800;color:#fff;letter-spacing:-1.5px;line-height:1;"><?php echo e(number_format($todayDeliveries)); ?></div>
            <div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:7px;">Completed today</div>
        </div>
    </div>

    
    <div class="col-xl-3 col-md-6">
        <div style="background:linear-gradient(135deg,#5b21b6 0%,#a78bfa 100%);border:none;border-radius:16px;padding:24px 22px;box-shadow:0 8px 24px rgba(139,92,246,.35);position:relative;overflow:hidden;transition:transform .2s,box-shadow .2s;" 
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 36px rgba(139,92,246,.45)'" 
             onmouseout="this.style.transform='';this.style.boxShadow='0 8px 24px rgba(139,92,246,.35)'">
            <div style="position:absolute;right:-18px;bottom:-18px;width:100px;height:100px;background:rgba(255,255,255,.1);border-radius:50%;"></div>
            <div style="position:absolute;right:28px;top:-20px;width:60px;height:60px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                <span style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.8px;color:rgba(255,255,255,.75);">Total Deliveries</span>
                <div style="width:40px;height:40px;background:rgba(255,255,255,.2);border-radius:11px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-box-check" style="color:#fff;font-size:16px;"></i>
                </div>
            </div>
            <div style="font-size:2.4rem;font-weight:800;color:#fff;letter-spacing:-1.5px;line-height:1;"><?php echo e(number_format($totalDeliveries)); ?></div>
            <div style="font-size:12px;color:rgba(255,255,255,.7);margin-top:7px;">All completed orders</div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <!-- Delivery Earnings Card -->
    <div class="col-xl-6">
        <div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;background:var(--teal-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-motorcycle" style="color:var(--teal);font-size:16px;"></i>
                </div>
                <span style="font-weight:700;font-size:14.5px;color:var(--text);">Today's Delivery Earnings (40%)</span>
            </div>
            <div style="padding:24px;">
                <!-- BIG TODAY NUMBER -->
                <h3 style="font-size:2.4rem;font-weight:800;color:var(--teal-dark);margin-bottom:8px;">
                    ₦<?php echo e(number_format($todayAdminDeliveryShare ?? 0, 2)); ?>

                </h3>
                <p class="text-muted mb-3">Amount you made today from delivery fees</p>
                
                <!-- SMALL TOTAL SUM -->
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <span class="small text-muted">Total Delivery Earnings</span><br>
                        <strong style="font-size:1.35rem;color:var(--teal-dark);">₦<?php echo e(number_format($totalAdminDeliveryShare ?? 0, 2)); ?></strong>
                    </div>
                    <div class="text-end">
                        <span class="small text-success">+ ₦<?php echo e(number_format($todayAdminDeliveryShare ?? 0, 2)); ?> today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Charges Card -->
    <div class="col-xl-6">
        <div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">
            <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;background:#f3e8ff;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-percentage" style="color:#7e22ce;font-size:16px;"></i>
                </div>
                <span style="font-weight:700;font-size:14.5px;color:var(--text);">Today's Service Charges (100%)</span>
            </div>
            <div style="padding:24px;">
                <!-- BIG TODAY NUMBER -->
                <h3 style="font-size:2.4rem;font-weight:800;color:#7e22ce;margin-bottom:8px;">
                    ₦<?php echo e(number_format($todayAdminServiceShare ?? 0, 2)); ?>

                </h3>
                <p class="text-muted mb-3">Full service charge you made today</p>
                
                <!-- SMALL TOTAL SUM -->
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <span class="small text-muted">Total Service Charges</span><br>
                        <strong style="font-size:1.35rem;color:#7e22ce;">₦<?php echo e(number_format($totalAdminServiceShare ?? 0, 2)); ?></strong>
                    </div>
                    <div class="text-end">
                        <span class="small text-success">+ ₦<?php echo e(number_format($todayAdminServiceShare ?? 0, 2)); ?> today</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3">
    
    

    
    <div class="col-xl-6">
        <div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;">

            <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;background:var(--teal-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-chart-line" style="color:var(--teal);font-size:13px;"></i>
                </div>
                <span style="font-weight:700;font-size:14.5px;color:var(--text);">Earnings Overview</span>
            </div>

            
            <div style="margin:20px 22px 0;background:linear-gradient(135deg,var(--teal-dark) 0%,var(--teal-light) 100%);border-radius:14px;padding:22px 24px;position:relative;overflow:hidden;">
                <div style="position:absolute;right:-10px;top:-10px;width:90px;height:90px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
                <div style="position:absolute;right:20px;bottom:-20px;width:70px;height:70px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
                <p style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.7);font-weight:700;margin-bottom:6px;">Today's Earnings</p>
                <h3 style="font-size:2rem;font-weight:800;color:#fff;letter-spacing:-1px;margin:0;">₦<?php echo e(number_format($todayEarnings, 2)); ?></h3>
                <div style="font-size:11.5px;color:rgba(255,255,255,.6);margin-top:4px;">
                    <i class="fas fa-bolt me-1"></i>Updated live
                </div>
            </div>

            
            <div style="margin:14px 22px 22px;background:linear-gradient(135deg,#c2410c 0%,var(--orange-light) 100%);border-radius:14px;padding:22px 24px;position:relative;overflow:hidden;">
                <div style="position:absolute;right:-10px;top:-10px;width:90px;height:90px;background:rgba(255,255,255,.08);border-radius:50%;"></div>
                <div style="position:absolute;right:20px;bottom:-20px;width:70px;height:70px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
                <p style="font-size:11px;text-transform:uppercase;letter-spacing:1px;color:rgba(255,255,255,.7);font-weight:700;margin-bottom:6px;">Total Earnings</p>
                <h4 style="font-size:2rem;font-weight:800;color:#fff;letter-spacing:-1px;margin:0;">₦<?php echo e(number_format($totalEarnings, 2)); ?></h4>
                <div style="font-size:11.5px;color:rgba(255,255,255,.6);margin-top:4px;">
                    <i class="fas fa-chart-line me-1"></i>All time
                </div>
            </div>

        </div>
    </div>

    
    <div class="col-xl-6">
        <div style="background:#fff;border:1px solid var(--border);border-radius:16px;box-shadow:var(--shadow-md);overflow:hidden;height:100%;">

            <div style="padding:18px 22px;border-bottom:1px solid var(--border-light);display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:32px;height:32px;background:var(--orange-soft);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-money-bill-wave" style="color:var(--orange);font-size:13px;"></i>
                    </div>
                    <span style="font-weight:700;font-size:14.5px;color:var(--text);">Recent Transactions</span>
                </div>
                <span style="font-size:11.5px;color:var(--muted);background:var(--bg);border:1px solid var(--border);border-radius:6px;padding:3px 10px;font-weight:600;">Last 20</span>
            </div>

            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td style="font-size:12px;color:var(--muted);font-weight:600;">#<?php echo e($tx->id); ?></td>
                                <td>
                                    <div style="display:flex;align-items:center;gap:8px;">
                                        <div style="width:28px;height:28px;background:var(--teal-soft);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:var(--teal);flex-shrink:0;">
                                            <?php echo e(strtoupper(substr($tx->user ? $tx->user->name : 'N', 0, 1))); ?>

                                        </div>
                                        <span style="font-size:13px;font-weight:500;"><?php echo e($tx->user ? $tx->user->name : 'N/A'); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-<?php echo e($tx->type == 'credit' ? 'success' : 'danger'); ?>">
                                        <?php echo e(ucfirst($tx->type)); ?>

                                    </span>
                                </td>
                                <td style="font-weight:700;font-size:13px;color:<?php echo e($tx->type == 'credit' ? 'var(--teal-dark)' : '#dc2626'); ?>;">
                                    ₦<?php echo e(number_format($tx->amount, 2)); ?>

                                </td>
                                <td style="font-size:12.5px;color:var(--muted);max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                    <?php echo e($tx->description); ?>

                                </td>
                                <td style="font-size:12px;color:var(--muted);">
                                    <?php echo e($tx->created_at->diffForHumans()); ?>

                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="fas fa-inbox" style="font-size:28px;color:var(--border);"></i>
                                    <p class="mt-2 text-muted">No transactions yet</p>
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


<?php $__env->startSection('scripts'); ?>
<script>
function refreshDashboard() {
    // Show loading effect
    const btn = document.querySelector('button[onclick="refreshDashboard()"]');
    const originalText = btn.innerHTML;
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
    btn.disabled = true;

    // Reload the page to get fresh wallet data
    window.location.reload();
}
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>