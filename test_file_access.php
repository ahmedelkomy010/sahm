<?php
echo "<h2>اختبار الوصول للملفات والصور</h2>";

// فحص بعض الملفات الموجودة
$files = [
    'work_orders/1/survey/1748849857_683d54c19ce0f.webp',
    'work_orders/2/installations/1747466373_682838850a4ef.webp',
    'work_orders/6/survey/1749536905_6847d08969be2.webp'
];

foreach ($files as $file) {
    echo "<h3>ملف: $file</h3>";
    
    $fullPath = "storage/app/public/$file";
    $publicPath = "public/storage/$file";
    
    echo "مسار التخزين: $fullPath<br>";
    echo "مسار Public: $publicPath<br>";
    
    if (file_exists($fullPath)) {
        echo "✅ الملف موجود في storage<br>";
        echo "حجم الملف: " . filesize($fullPath) . " بايت<br>";
    } else {
        echo "❌ الملف غير موجود في storage<br>";
    }
    
    if (file_exists($publicPath)) {
        echo "✅ الملف موجود في public/storage<br>";
    } else {
        echo "❌ الملف غير موجود في public/storage<br>";
    }
    
    // فحص الوصول عبر HTTP
    $urls = [
        "http://localhost/sahm/storage/$file",
        "http://localhost/sahm/public/storage/$file",
        "http://localhost/sahm/files/$file"
    ];
    
    foreach ($urls as $url) {
        echo "فحص الرابط: $url<br>";
        $headers = @get_headers($url);
        if ($headers) {
            if (strpos($headers[0], '200') !== false) {
                echo "✅ متاح - " . $headers[0] . "<br>";
            } else {
                echo "❌ غير متاح - " . $headers[0] . "<br>";
            }
        } else {
            echo "❌ لا يمكن الوصول للرابط<br>";
        }
    }
    
    echo "<hr>";
}

// عرض صورة تجريبية مباشرة
$testImage = 'work_orders/6/survey/1749536905_6847d08969be2.webp';
echo "<h3>عرض صورة تجريبية:</h3>";
echo "<img src='/sahm/storage/$testImage' alt='Test Image' style='max-width: 200px;'><br>";
echo "<img src='/sahm/public/storage/$testImage' alt='Test Image 2' style='max-width: 200px;'><br>";
echo "<img src='/sahm/files/$testImage' alt='Test Image 3' style='max-width: 200px;'><br>";
?> 