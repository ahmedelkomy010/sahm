<?php
// ملف تجريبي لفحص مسارات الملفات والتأكد من إعدادات storage

echo "<h2>فحص إعدادات Storage والملفات</h2>";

// فحص APP_URL
echo "<h3>إعدادات التطبيق:</h3>";
echo "APP_URL: " . env('APP_URL', 'غير محدد') . "<br>";
echo "APP_ENV: " . env('APP_ENV', 'غير محدد') . "<br>";

// فحص مسارات storage
echo "<h3>مسارات Storage:</h3>";
echo "Storage path: " . storage_path() . "<br>";
echo "Public storage path: " . storage_path('app/public') . "<br>";
echo "Public path: " . public_path() . "<br>";
echo "Storage link path: " . public_path('storage') . "<br>";

// فحص وجود الربط
echo "<h3>فحص الربط:</h3>";
if (file_exists(public_path('storage'))) {
    echo "✅ رابط storage موجود<br>";
    if (is_link(public_path('storage'))) {
        echo "✅ هو رابط رمزي صحيح<br>";
        echo "يشير إلى: " . readlink(public_path('storage')) . "<br>";
    } else {
        echo "⚠️ هو مجلد عادي وليس رابط رمزي<br>";
    }
} else {
    echo "❌ رابط storage غير موجود<br>";
}

// فحص ملف تجريبي
$testFilePath = 'test_file.txt';
$fullTestPath = storage_path('app/public/' . $testFilePath);

// إنشاء ملف تجريبي
file_put_contents($fullTestPath, 'هذا ملف تجريبي');

echo "<h3>فحص رابط ملف تجريبي:</h3>";
if (file_exists($fullTestPath)) {
    echo "✅ الملف التجريبي موجود في: " . $fullTestPath . "<br>";
    
    // جرب Storage::url
    try {
        $storageUrl = \Illuminate\Support\Facades\Storage::url($testFilePath);
        echo "Storage::url: " . $storageUrl . "<br>";
        
        // فحص إمكانية الوصول
        $headers = @get_headers($storageUrl);
        if ($headers && strpos($headers[0], '200') !== false) {
            echo "✅ يمكن الوصول للملف عبر Storage::url<br>";
        } else {
            echo "❌ لا يمكن الوصول للملف عبر Storage::url<br>";
        }
    } catch (Exception $e) {
        echo "❌ خطأ في Storage::url: " . $e->getMessage() . "<br>";
    }
    
    // جرب asset
    $assetUrl = asset('storage/' . $testFilePath);
    echo "asset('storage/'): " . $assetUrl . "<br>";
    
    $headers = @get_headers($assetUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "✅ يمكن الوصول للملف عبر asset<br>";
    } else {
        echo "❌ لا يمكن الوصول للملف عبر asset<br>";
    }
} else {
    echo "❌ لا يمكن إنشاء ملف تجريبي<br>";
}

// فحص أذونات المجلدات
echo "<h3>أذونات المجلدات:</h3>";
$dirs = [
    'storage/app/public' => storage_path('app/public'),
    'public/storage' => public_path('storage')
];

foreach ($dirs as $name => $path) {
    if (file_exists($path)) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        echo "$name: $perms<br>";
    } else {
        echo "$name: غير موجود<br>";
    }
}

// تنظيف
if (file_exists($fullTestPath)) {
    unlink($fullTestPath);
}
?> 