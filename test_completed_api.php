<?php

require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Api\WorkOrderController;
use Illuminate\Http\Request;

try {
    $controller = new WorkOrderController();
    $request = new Request([
        'project' => 'riyadh',
        'page' => 1,
        'per_page' => 25
    ]);
    
    echo "Testing completed API...\n";
    $response = $controller->completed($request);
    
    if ($response->getStatusCode() === 200) {
        $data = json_decode($response->getContent(), true);
        echo "✓ API call successful\n";
        echo "Success: " . ($data['success'] ? 'true' : 'false') . "\n";
        echo "Total orders: " . ($data['summary']['total_orders'] ?? 0) . "\n";
        echo "Total final value: " . ($data['summary']['total_final_value'] ?? 0) . "\n";
    } else {
        echo "✗ API call failed with status: " . $response->getStatusCode() . "\n";
        echo "Response: " . $response->getContent() . "\n";
    }
    
} catch (\Exception $e) {
    echo "✗ Exception occurred: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
