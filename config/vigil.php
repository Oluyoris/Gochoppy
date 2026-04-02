<?php

return [
    'checks' => [
        'fs.public_folder'       => true,
        'fs.malicious_js'        => true,
        'fs.storage_dangerous'   => true,
        'fs.permissions'         => true,
        'fs.sensitive_exposure'  => true,
        'cfg.php_ini'            => true,
        'cfg.env'                => true,
        'cfg.session'            => true,
        'cfg.cors'               => true,
        'http.headers'           => false,
        'dep.composer_audit'     => false,   // keep off for now
        'ext.hardcoded_secrets'  => false,   // keep off (false positive)
        'ext.debug_routes'       => true,
        'ext.telescope_debugbar' => true,
        'ext.file_integrity'     => false,
    ],

    'public_allowed_extensions' => [
        'css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp',
        'ico', 'woff', 'woff2', 'ttf', 'eot', 'pdf', 'map', 'txt',
    ],

    'storage_dangerous_extensions' => [
        'php', 'phtml', 'phar', 'php3', 'php4', 'php5', 'php7',
        'exe', 'bat', 'cmd', 'sh', 'bash', 'bin', 'js', 'vbs', 'ps1',
    ],

    'store_results' => true,
    'results_retention_days' => 90,

    'notifications' => [
        'enabled' => false,
        'channels' => ['mail'],
        'notify_on_severity' => ['critical', 'high'],
        'mail_to' => env('VIGIL_MAIL_TO', null),
    ],

    // ← ADD THIS LINE
    'app_url' => env('VIGIL_APP_URL', 'http://0.0.0.0:8000'),
];