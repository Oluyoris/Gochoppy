<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gochoppy Admin - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal:         #0d9488;
            --teal-light:   #14b8a6;
            --teal-dark:    #0f766e;
            --teal-soft:    #f0fdfa;
            --orange:       #f97316;
            --orange-light: #fb923c;
            --orange-soft:  #fff7ed;
            --bg:           #f8fafc;
            --white:        #ffffff;
            --border:       #e2e8f0;
            --border-light: #f1f5f9;
            --text:         #0f172a;
            --text-body:    #334155;
            --muted:        #94a3b8;
            --nav-h:        64px;
            --subnav-h:     48px;
            --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md:    0 4px 16px rgba(0,0,0,.07), 0 2px 6px rgba(0,0,0,.04);
            --shadow-lg:    0 10px 30px rgba(0,0,0,.08), 0 4px 12px rgba(0,0,0,.04);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            color: var(--text-body);
            padding-top: calc(var(--nav-h) + var(--subnav-h));
            padding-bottom: 60px;
            font-size: 14px;
            -webkit-font-smoothing: antialiased;
        }

        ::-webkit-scrollbar { width: 5px; height: 4px; }
        ::-webkit-scrollbar-track { background: var(--border-light); }
        ::-webkit-scrollbar-thumb { background: var(--teal-light); border-radius: 4px; }

        /* ══════════════════════════════════
           TOP BAR
        ══════════════════════════════════ */
        .gc-topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--nav-h);
            background: linear-gradient(135deg, var(--teal-dark) 0%, var(--teal) 60%, #0e9f97 100%);
            box-shadow: 0 4px 20px rgba(13,148,136,.35);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            z-index: 1000;
        }

        .gc-topbar .brand {
            display: flex; align-items: center; gap: 11px; text-decoration: none;
        }
        .gc-topbar .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--teal) 0%, var(--orange) 100%);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff;
            box-shadow: 0 4px 14px rgba(13,148,136,.35);
            flex-shrink: 0;
        }
        .gc-topbar .brand-text { font-weight: 800; font-size: 1.2rem; color: #fff; letter-spacing: -.3px; }
        .gc-topbar .brand-text span { color: #fde68a; }

        .gc-topbar .topbar-right { display: flex; align-items: center; gap: 12px; }

        .gc-topbar .admin-pill {
            display: flex; align-items: center; gap: 9px;
            background: rgba(255,255,255,.18);
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 40px;
            padding: 5px 16px 5px 5px;
        }
        .gc-topbar .admin-avatar {
            width: 32px; height: 32px;
            background: rgba(255,255,255,.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }
        .gc-topbar .admin-name { font-size: 13.5px; font-weight: 600; color: #fff; }

        .gc-topbar .btn-logout {
            display: flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,.15);
            border: 1.5px solid rgba(255,255,255,.35);
            color: #fff; border-radius: 9px;
            padding: 8px 16px; font-size: 13px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer; transition: all .2s;
        }
        .gc-topbar .btn-logout:hover {
            background: rgba(249,115,22,.85); border-color: var(--orange); color: #fff;
        }

        /* ══════════════════════════════════
           NAV BAR
        ══════════════════════════════════ */
        .gc-navbar {
            position: fixed;
            top: var(--nav-h); left: 0; right: 0;
            height: var(--subnav-h);
            background: var(--teal-dark);
            display: flex; align-items: stretch;
            padding: 0 24px;
            z-index: 999;
            overflow-x: auto; overflow-y: visible;
            gap: 0;
            box-shadow: 0 3px 12px rgba(13,148,136,.25);
        }
        .gc-navbar::-webkit-scrollbar { height: 2px; }

        .gc-navbar a.nav-link-item {
            display: inline-flex; align-items: center; gap: 7px;
            color: rgba(255,255,255,.65);
            text-decoration: none;
            font-size: 13px; font-weight: 600;
            padding: 0 14px;
            border-bottom: 2.5px solid transparent;
            white-space: nowrap; flex-shrink: 0;
            transition: color .18s, border-color .18s, background .18s;
        }
        .gc-navbar a.nav-link-item i { font-size: 12px; }
        .gc-navbar a.nav-link-item:hover { color: #fff; background: rgba(255,255,255,.08); }
        .gc-navbar a.nav-link-item.active {
            color: #fff; border-bottom-color: var(--orange); background: rgba(255,255,255,.1);
        }

        .gc-navbar .nav-sep {
            width: 1px; background: rgba(255,255,255,.15);
            margin: 10px 6px; flex-shrink: 0;
        }

        /* SETTINGS DROPDOWN */
        .nav-dropdown {
            position: relative;
            display: flex;
            align-items: stretch;
            flex-shrink: 0;
        }

        .nav-dropdown-trigger {
            display: inline-flex; align-items: center; gap: 7px;
            color: rgba(255,255,255,.65);
            background: none; border: none; outline: none;
            font-size: 13px; font-weight: 600;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 0 14px;
            border-bottom: 2.5px solid transparent;
            white-space: nowrap;
            cursor: pointer;
            height: 100%;
            transition: color .18s, border-color .18s, background .18s;
        }
        .nav-dropdown-trigger i { font-size: 12px; }
        .nav-dropdown-trigger .chevron {
            font-size: 10px;
            margin-left: 2px;
            transition: transform .22s;
        }
        .nav-dropdown-trigger:hover,
        .nav-dropdown.open .nav-dropdown-trigger {
            color: #fff; background: rgba(255,255,255,.08);
        }
        .nav-dropdown-trigger.active {
            color: #fff;
            border-bottom-color: var(--orange);
            background: rgba(255,255,255,.1);
        }
        .nav-dropdown.open .nav-dropdown-trigger .chevron {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            display: none;
            position: fixed;
            top: calc(var(--nav-h) + var(--subnav-h));
            min-width: 220px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 12px 40px rgba(0,0,0,.13), 0 4px 12px rgba(0,0,0,.06);
            padding: 6px;
            z-index: 1100;
            animation: dropIn .18s ease;
        }
        .nav-dropdown.open .nav-dropdown-menu {
            display: block;
        }

        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .nav-dropdown-menu a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 14px;
            border-radius: 8px;
            color: var(--text-body);
            text-decoration: none;
            font-size: 13px; font-weight: 600;
            transition: background .15s, color .15s;
            white-space: nowrap;
        }
        .nav-dropdown-menu a i {
            width: 18px; text-align: center;
            font-size: 13px; color: var(--teal);
        }
        .nav-dropdown-menu a:hover {
            background: var(--teal-soft); color: var(--teal-dark);
        }
        .nav-dropdown-menu a:hover i { color: var(--teal-dark); }
        .nav-dropdown-menu a.active {
            background: var(--teal-soft); color: var(--teal-dark); font-weight: 700;
        }
        .nav-dropdown-menu a.active i { color: var(--teal-dark); }

        .nav-dropdown-menu .dd-sep {
            height: 1px; background: var(--border-light);
            margin: 4px 8px;
        }

        .main-content {
            max-width: 1440px; margin: 0 auto; padding: 30px 32px 24px;
        }

        .gc-footer {
            position: fixed; bottom: 0; left: 0; right: 0; height: 48px;
            background: linear-gradient(90deg, var(--teal-dark) 0%, var(--orange) 100%);
            display: flex; align-items: center; justify-content: center; z-index: 900;
        }
        .gc-footer small { color: rgba(255,255,255,.9); font-size: 12px; font-weight: 500; }

        .card { background: var(--white); border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow-sm); }
        .card-header {
            background: var(--white) !important; border-bottom: 1px solid var(--border-light);
            padding: 16px 20px; font-weight: 700; font-size: 14px;
            color: var(--text) !important; border-radius: 14px 14px 0 0 !important;
        }
        .table { color: var(--text-body); }
        .table thead th {
            background: var(--bg); color: var(--muted); font-size: 11px;
            text-transform: uppercase; letter-spacing: .9px; font-weight: 700;
            border-color: var(--border-light); padding: 10px 16px;
        }
        .table tbody td { border-color: var(--border-light); padding: 12px 16px; vertical-align: middle; color: var(--text-body); }
        .table tbody tr:hover { background: var(--bg); }
        .form-control, .form-select {
            border: 1.5px solid var(--border); border-radius: 9px;
            color: var(--text); background: var(--white);
            font-family: 'Plus Jakarta Sans', sans-serif; font-size: 13.5px;
        }
        .form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(13,148,136,.12); }
        .form-label { font-weight: 600; font-size: 13px; color: var(--text); }
        .btn-primary { background: var(--teal); border-color: var(--teal); font-weight: 600; border-radius: 9px; }
        .btn-primary:hover { background: var(--teal-dark); border-color: var(--teal-dark); }
        h2, h3, h4, h5, h6 { color: var(--text); font-weight: 700; }
        .badge.bg-success { background: #dcfce7 !important; color: #16a34a !important; font-weight: 600; }
        .badge.bg-danger  { background: #fee2e2 !important; color: #dc2626 !important; font-weight: 600; }
        .badge.bg-warning { background: #fef9c3 !important; color: #ca8a04 !important; font-weight: 600; }
        .badge.bg-info    { background: #dbeafe !important; color: #2563eb !important; font-weight: 600; }
        .alert-success { background: var(--teal-soft); border-color: #99f6e4; color: var(--teal-dark); }
        .alert-danger  { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
        .alert-warning { background: var(--orange-soft); border-color: #fed7aa; color: #c2410c; }
    </style>
</head>
<body>

<!-- TOP BAR -->
<div class="gc-topbar">
    <a class="brand" href="<?php echo e(route('admin.dashboard')); ?>">
        <div class="brand-icon"><i class="fas fa-truck"></i></div>
        <span class="brand-text">Go<span>Choppy</span> <span style="color:var(--muted);font-size:.75rem;font-weight:500;">Admin</span></span>
    </a>
    <div class="topbar-right">
        <?php if(Auth::guard('admin')->check()): ?>
        <div class="admin-pill">
            <div class="admin-avatar"><?php echo e(strtoupper(substr(Auth::guard('admin')->user()->name, 0, 2))); ?></div>
            <span class="admin-name"><?php echo e(Auth::guard('admin')->user()->name); ?></span>
        </div>
        <?php endif; ?>
        <form method="POST" action="<?php echo e(route('admin.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
    </div>
</div>

<!-- NAV BAR -->
<nav class="gc-navbar" id="gcNavbar">

    <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
        <i class="fas fa-gauge-high"></i> Dashboard
    </a>

    <a href="<?php echo e(route('admin.users.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.users.*') ? 'active' : ''); ?>">
        <i class="fas fa-users"></i> Users
    </a>

    <a href="<?php echo e(route('admin.vendors.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.vendors.*') ? 'active' : ''); ?>">
        <i class="fas fa-store"></i> Vendors
    </a>

    <a href="<?php echo e(route('admin.dispatchers.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.dispatchers.*') ? 'active' : ''); ?>">
        <i class="fas fa-motorcycle"></i> Dispatchers
    </a>

    <div class="nav-sep"></div>

    <a href="<?php echo e(route('admin.orders.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.orders.*') ? 'active' : ''); ?>">
        <i class="fas fa-shopping-cart"></i> Orders
    </a>

    <a href="<?php echo e(route('admin.transactions.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.transactions.*') ? 'active' : ''); ?>">
        <i class="fas fa-money-bill"></i> Transactions
    </a>

    <a href="<?php echo e(route('admin.withdrawals.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.withdrawals.*') ? 'active' : ''); ?>">
        <i class="fas fa-hand-holding-dollar"></i> Withdrawals
    </a>

    <a href="<?php echo e(route('admin.items.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.items.*') ? 'active' : ''); ?>">
        <i class="fas fa-boxes-stacked"></i> Items
    </a>

    <a href="<?php echo e(route('admin.menu-requests.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.menu-requests.*') ? 'active' : ''); ?>">
        <i class="fas fa-list"></i> Menu Requests
    </a>

    <a href="<?php echo e(route('admin.deposits.index')); ?>" class="nav-link-item <?php echo e(request()->routeIs('admin.deposits.*') ? 'active' : ''); ?>">
        <i class="fas fa-hand-holding-dollar"></i> Customer Deposits
    </a>

    <div class="nav-sep"></div>

    
    <?php
        $settingsActive = request()->routeIs('admin.settings.*')
                       || request()->routeIs('admin.subscriptions.*')
                       || request()->routeIs('admin.sub-admins.*')
                       || request()->routeIs('admin.bus-stops.*')
                       || request()->routeIs('admin.coupons.*');
    ?>

    <div class="nav-dropdown" id="settingsDropdown">
        <button
            class="nav-dropdown-trigger <?php echo e($settingsActive ? 'active' : ''); ?>"
            onclick="toggleDropdown('settingsDropdown', event)"
            type="button"
        >
            <i class="fas fa-cog"></i>
            Settings
            <i class="fas fa-chevron-down chevron"></i>
        </button>

        <div class="nav-dropdown-menu" id="settingsDropdownMenu">

            <a href="<?php echo e(route('admin.settings.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.settings.*') ? 'active' : ''); ?>">
                <i class="fas fa-sliders"></i> General Settings
            </a>

            <!-- COUPON ADDED HERE -->
            <a href="<?php echo e(route('admin.coupons.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.coupons.*') ? 'active' : ''); ?>">
                <i class="fas fa-ticket-alt"></i> Coupons & Vouchers
            </a>

            <div class="dd-sep"></div>

            <a href="<?php echo e(route('admin.subscriptions.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.subscriptions.*') ? 'active' : ''); ?>">
                <i class="fas fa-crown"></i> Subscriptions
            </a>

            <a href="<?php echo e(route('admin.sub-admins.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.sub-admins.*') ? 'active' : ''); ?>">
                <i class="fas fa-user-shield"></i> Sub Admins
            </a>

            <a href="<?php echo e(route('admin.bus-stops.index')); ?>"
               class="<?php echo e(request()->routeIs('admin.bus-stops.*') ? 'active' : ''); ?>">
                <i class="fas fa-map-marker-alt"></i> Bus Stops
            </a>

        </div>
    </div>

</nav>

<!-- MAIN CONTENT -->
<div class="main-content">
    <?php echo $__env->yieldContent('content'); ?>
</div>

<!-- FOOTER -->
<footer class="gc-footer">
    <small>© <?php echo e(date('Y')); ?> GoChoppy - Admin Panel | All Rights Reserved</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleDropdown(dropdownId, event) {
    const dropdown = document.getElementById(dropdownId);
    const menu     = dropdown.querySelector('.nav-dropdown-menu');
    const trigger  = dropdown.querySelector('.nav-dropdown-trigger');
    const isOpen   = dropdown.classList.contains('open');

    document.querySelectorAll('.nav-dropdown.open').forEach(function(d) {
        if (d.id !== dropdownId) d.classList.remove('open');
    });

    if (isOpen) {
        dropdown.classList.remove('open');
    } else {
        const rect = trigger.getBoundingClientRect();
        menu.style.left = rect.left + 'px';
        dropdown.classList.add('open');
    }

    event.stopPropagation();
}

document.addEventListener('click', function() {
    document.querySelectorAll('.nav-dropdown.open').forEach(function(d) {
        d.classList.remove('open');
    });
});

document.querySelectorAll('.nav-dropdown-menu').forEach(function(menu) {
    menu.addEventListener('click', function(e) { e.stopPropagation(); });
});
</script>
</body>
</html><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/layouts/admin.blade.php ENDPATH**/ ?>