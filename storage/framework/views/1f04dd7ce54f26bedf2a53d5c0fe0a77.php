<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GoChoppy Admin — <?php echo $__env->yieldContent('title', 'Login'); ?></title>
    <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --teal:         #0d9488;
            --teal-light:   #14b8a6;
            --teal-dark:    #0f766e;
            --orange:       #f97316;
            --orange-light: #fb923c;
            --bg:           #f0fdfa;
            --white:        #ffffff;
            --border:       #e2e8f0;
            --text:         #0f172a;
            --text-body:    #334155;
            --muted:        #94a3b8;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow: hidden;
        }

        /* ── Decorative background shapes ── */
        body::before {
            content: '';
            position: fixed;
            top: -120px; left: -120px;
            width: 480px; height: 480px;
            background: radial-gradient(circle, rgba(13,148,136,.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -140px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(249,115,22,.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ── Top strip ── */
        .auth-topstrip {
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--teal-dark), var(--teal-light), var(--orange));
            flex-shrink: 0;
        }

        /* ── Main wrapper ── */
        .auth-wrap {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        /* ── Brand block above card ── */
        .auth-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            text-decoration: none;
        }

        .auth-brand .logo-box {
            width: 48px; height: 48px;
            background: linear-gradient(135deg, var(--teal-dark), var(--orange));
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            box-shadow: 0 8px 20px rgba(13,148,136,.35);
        }

        .auth-brand .logo-text {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--text);
            letter-spacing: -.5px;
        }

        .auth-brand .logo-text span { color: var(--orange); }

        .auth-brand .logo-sub {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            display: block;
            line-height: 1;
            margin-top: 2px;
        }

        /* ── Card ── */
        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--white);
            border: 1px solid rgba(13,148,136,.12);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(13,148,136,.12), 0 4px 16px rgba(0,0,0,.06);
            overflow: hidden;
        }

        /* card-header override */
        .login-card .card-header {
            background: linear-gradient(135deg, var(--teal-dark) 0%, var(--teal-light) 100%) !important;
            border: none !important;
            padding: 28px 32px 24px !important;
            border-radius: 0 !important;
            text-align: center;
        }

        .login-card .card-header h4 {
            color: #fff !important;
            font-weight: 800;
            font-size: 1.25rem;
            margin: 0;
            letter-spacing: -.2px;
        }

        .login-card .card-header p {
            color: rgba(255,255,255,.72);
            font-size: 13px;
            margin: 6px 0 0;
        }

        /* card-body override */
        .login-card .card-body {
            padding: 32px !important;
        }

        /* ── Form elements ── */
        .form-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 6px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 14px;
            pointer-events: none;
        }

        .input-wrap .form-control {
            padding-left: 40px;
            height: 46px;
            border: 1.5px solid var(--border);
            border-radius: 10px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: var(--text);
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        .input-wrap .form-control:focus {
            border-color: var(--teal);
            box-shadow: 0 0 0 3px rgba(13,148,136,.12);
            outline: none;
        }

        .input-wrap .form-control.is-invalid {
            border-color: #f87171;
        }

        .invalid-feedback { font-size: 12px; font-weight: 500; }

        /* ── Submit button ── */
        .btn-signin {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, var(--teal-dark), var(--teal-light));
            border: none;
            border-radius: 11px;
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            letter-spacing: .2px;
            box-shadow: 0 6px 20px rgba(13,148,136,.3);
            transition: all .2s;
        }

        .btn-signin:hover {
            background: linear-gradient(135deg, #065f46, var(--teal));
            box-shadow: 0 8px 26px rgba(13,148,136,.4);
            transform: translateY(-1px);
        }

        /* btn-primary override for inside content pages */
        .btn-primary {
            background: var(--teal) !important;
            border-color: var(--teal) !important;
            font-weight: 600;
            border-radius: 9px;
        }
        .btn-primary:hover {
            background: var(--teal-dark) !important;
            border-color: var(--teal-dark) !important;
        }

        /* ── Alerts ── */
        .alert {
            border-radius: 10px;
            font-size: 13px;
            padding: 12px 16px;
        }
        .alert-success {
            background: #f0fdfa;
            border-color: #99f6e4;
            color: var(--teal-dark);
        }
        .alert-danger {
            background: #fef2f2;
            border-color: #fecaca;
            color: #b91c1c;
        }
        .alert ul { padding-left: 16px; }

        /* ── Footer strip ── */
        .auth-footer {
            text-align: center;
            padding: 18px 16px;
            font-size: 12px;
            color: var(--muted);
            flex-shrink: 0;
        }

        .auth-footer span { color: var(--teal); font-weight: 600; }
    </style>
</head>
<body>

<div class="auth-topstrip"></div>

<div class="auth-wrap">

    <!-- Brand -->
    <div class="auth-brand">
        <div class="logo-box"><i class="fas fa-truck"></i></div>
        <div>
            <span class="logo-text">Go<span>Choppy</span></span>
            <span class="logo-sub">Admin Portal</span>
        </div>
    </div>

    <!-- Page content (login card) -->
    <?php echo $__env->yieldContent('content'); ?>

</div>

<div class="auth-footer">
    © <?php echo e(date('Y')); ?> GoChoppy &mdash; <span>Admin Panel</span>. All Rights Reserved.
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html><?php /**PATH C:\Users\hp\Projects\gochoppy\backend\resources\views/layouts/admin-auth.blade.php ENDPATH**/ ?>