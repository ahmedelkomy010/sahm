<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Routes:\n";
echo "=================\n\n";

try {
    // Test actions-execution route
    $route1 = route('admin.work-orders.actions-execution', ['workOrder' => 1]);
    echo "✓ admin.work-orders.actions-execution: " . $route1 . "\n";
} catch (Exception $e) {
    echo "✗ admin.work-orders.actions-execution: " . $e->getMessage() . "\n";
}

try {
    // Test upload-post-execution-file route
    $route2 = route('admin.work-orders.upload-post-execution-file', ['workOrder' => 1]);
    echo "✓ admin.work-orders.upload-post-execution-file: " . $route2 . "\n";
} catch (Exception $e) {
    echo "✗ admin.work-orders.upload-post-execution-file: " . $e->getMessage() . "\n";
}

echo "\nTest completed!\n"; 