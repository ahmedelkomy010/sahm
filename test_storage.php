<?php
// إختبار بسيط لفحص storage

echo "<h2>فحص storage والملفات</h2>";

// فحص storage link
if (is_link('public/storage')) {
    echo "✅ Storage link موجود<br>";
} else {
    echo "❌ Storage link غير موجود<br>";
}

// فحص وجود مجلدات storage
$dirs = [
    'storage/app/public',
    'public/storage'
];

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir موجود<br>";
    } else {
        echo "❌ $dir غير موجود<br>";
    }
}

// إنشاء ملف تجريبي
$testFile = 'storage/app/public/test.txt';
file_put_contents($testFile, 'test content');

// فحص الوصول للملف
$urls = [
    '/storage/test.txt',
    '/public/storage/test.txt',
    '/files/test.txt'
];

foreach ($urls as $url) {
    $fullUrl = 'http://localhost/sahm' . $url;
    $headers = @get_headers($fullUrl);
    if ($headers && strpos($headers[0], '200') !== false) {
        echo "✅ يمكن الوصول عبر: $url<br>";
    } else {
        echo "❌ لا يمكن الوصول عبر: $url<br>";
    }
}

// تنظيف
if (file_exists($testFile)) {
    unlink($testFile);
}
?> 