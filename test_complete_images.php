<?php
echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>اختبار شامل لعرض الصور والمرفقات</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .test-section { background: white; margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .image-test { display: inline-block; margin: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 4px; background: #fafafa; }
        .image-test img { max-width: 150px; max-height: 150px; display: block; margin-bottom: 5px; }
        .success { border-color: #28a745; background: #d4edda; }
        .error { border-color: #dc3545; background: #f8d7da; }
        .info { font-size: 12px; color: #666; word-break: break-all; }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        h3 { color: #555; }
    </style>
</head>
<body>";

echo "<h1>🔍 اختبار شامل لعرض الصور والمرفقات</h1>";

// قائمة الصور للاختبار
$testImages = [
    // صور موجودة فعلياً
    'work_orders/6/survey/1749536914_6847d092c9a78.webp',
    'work_orders/6/survey/1749536914_6847d092d9dc1.webp',
    'work_orders/6/survey/1749536914_6847d092db8a1.webp',
    
    // صور مفقودة (من رسائل الخطأ)
    'work_orders/6/survey/174970428_684a607c1cc27.png',
    'work_orders/6/survey/174970428_684a607c267f9.png',
    'work_orders/6/survey/174970428_684a607c28172.png',
    'work_orders/6/survey/174970428_684a607c29f01.png',
];

echo "<div class='test-section'>";
echo "<h2>📊 معلومات النظام</h2>";
echo "<p><strong>المجلد الحالي:</strong> " . __DIR__ . "</p>";
echo "<p><strong>مجلد Storage:</strong> " . (__DIR__ . "/storage/app/public") . "</p>";
echo "<p><strong>مجلد Public/Storage:</strong> " . (__DIR__ . "/public/storage") . "</p>";
echo "<p><strong>وجود الرابط الرمزي:</strong> " . (is_link(__DIR__ . "/public/storage") ? "✅ موجود" : "❌ غير موجود") . "</p>";
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>🔍 فحص الملفات</h2>";
foreach ($testImages as $image) {
    $storagePath = __DIR__ . "/storage/app/public/" . $image;
    $publicPath = __DIR__ . "/public/storage/" . $image;
    
    echo "<div style='margin: 10px 0; padding: 10px; background: #f8f9fa; border-radius: 4px;'>";
    echo "<strong>📁 {$image}</strong><br>";
    echo "Storage: " . (file_exists($storagePath) ? "✅ موجود (" . filesize($storagePath) . " بايت)" : "❌ غير موجود") . "<br>";
    echo "Public: " . (file_exists($publicPath) ? "✅ موجود" : "❌ غير موجود") . "<br>";
    echo "</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>🖼️ طرق عرض الصور المختلفة</h2>";

foreach ($testImages as $i => $image) {
    echo "<div style='margin: 30px 0; padding: 20px; border: 2px solid #e9ecef; border-radius: 8px;'>";
    echo "<h3>صورة " . ($i + 1) . ": " . basename($image) . "</h3>";
    
    $methods = [
        'المسار المباشر - storage' => "/sahm/storage/{$image}",
        'المسار المباشر - public' => "/sahm/public/storage/{$image}",
        'serve_file.php' => "/sahm/serve_file.php?file=" . urlencode($image),
        'serve_file_enhanced.php' => "/sahm/serve_file_enhanced.php?file=" . urlencode($image),
        'no-image.png' => "/sahm/public/images/no-image.png"
    ];
    
    foreach ($methods as $methodName => $url) {
        echo "<div class='image-test' id='test-{$i}-" . str_replace(' ', '-', $methodName) . "'>";
        echo "<strong>{$methodName}</strong><br>";
        echo "<img src='{$url}' alt='{$methodName}' onload='markSuccess(this)' onerror='markError(this)'>";
        echo "<div class='info'>{$url}</div>";
        echo "</div>";
    }
    
    echo "</div>";
}
echo "</div>";

echo "<div class='test-section'>";
echo "<h2>🔧 اختبارات تقنية إضافية</h2>";

// اختبار خدمة الملفات
$testUrls = [
    "/sahm/serve_file_enhanced.php?file=work_orders/6/survey/test.png",
    "/sahm/serve_file_enhanced.php?file=non-existent-file.jpg",
    "/sahm/public/images/no-image.png"
];

foreach ($testUrls as $url) {
    echo "<div class='image-test'>";
    echo "<strong>اختبار: " . basename($url) . "</strong><br>";
    echo "<img src='{$url}' alt='Test' onload='markSuccess(this)' onerror='markError(this)'>";
    echo "<div class='info'>{$url}</div>";
    echo "</div>";
}

echo "</div>";

echo "<script>
function markSuccess(img) {
    img.parentElement.classList.remove('error');
    img.parentElement.classList.add('success');
    img.title = 'تم تحميل الصورة بنجاح';
}

function markError(img) {
    img.parentElement.classList.remove('success');
    img.parentElement.classList.add('error');
    img.title = 'فشل في تحميل الصورة';
}

// إضافة معلومات إضافية
window.onload = function() {
    console.log('📊 معلومات الاختبار:');
    console.log('- عدد الصور المختبرة: " . count($testImages) . "');
    console.log('- المجلد الحالي: " . __DIR__ . "');
    
    // فحص جميع الصور بعد التحميل
    setTimeout(function() {
        const successImages = document.querySelectorAll('.success').length;
        const errorImages = document.querySelectorAll('.error').length;
        const totalImages = document.querySelectorAll('.image-test').length;
        
        console.log(\`✅ نجح: \${successImages}, ❌ فشل: \${errorImages}, 📊 المجموع: \${totalImages}\`);
        
        // إظهار تقرير
        const report = document.createElement('div');
        report.innerHTML = \`
            <div style='position: fixed; top: 10px; right: 10px; background: #fff; padding: 15px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.2); z-index: 1000;'>
                <h4>📊 تقرير الاختبار</h4>
                <p>✅ نجح: \${successImages}</p>
                <p>❌ فشل: \${errorImages}</p>
                <p>📊 المجموع: \${totalImages}</p>
                <button onclick='this.parentElement.remove()'>إغلاق</button>
            </div>
        \`;
        document.body.appendChild(report);
    }, 3000);
};
</script>";

echo "</body></html>";
?> 