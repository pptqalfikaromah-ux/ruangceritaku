<?php

// Ensure /tmp directory structure for Laravel on Vercel Serverless
$storageDirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Forward Vercel serverless request to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
