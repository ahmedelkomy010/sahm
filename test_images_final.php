<?php
echo "<h2>اختبار نهائي لعرض الصور والمرفقات</h2>";

// قائمة بالصور الموجودة فعلياً
$testImages = [
    'work_orders/6/survey/1749536914_6847d092c9a78.webp',
    'work_orders/6/survey/1749536914_6847d092d9dc1.webp', 
    'work_orders/6/survey/1749536914_6847d092db8a1.webp'
];

echo "<h3>طرق مختلفة لعرض الصور:</h3>";

foreach ($testImages as $i => $image) {
    echo "<div style='margin: 20px; padding: 20px; border: 1px solid #ccc;'>";
    echo "<h4>صورة " . ($i + 1) . ": $image</h4>";
    
    // طريقة 1: Storage::url العادي
    $url1 = "/sahm/storage/$image";
    echo "<p>1. Storage URL العادي:</p>";
    echo "<img src='$url1' alt='Test 1' style='max-width: 200px; margin: 5px;' onerror='this.style.border=\"2px solid red\"'>";
    echo "<br><small>$url1</small><br>";
    
    // طريقة 2: serve_file.php
    $url2 = "/sahm/serve_file.php?file=" . urlencode($image);
    echo "<p>2. serve_file.php:</p>";
    echo "<img src='$url2' alt='Test 2' style='max-width: 200px; margin: 5px;' onerror='this.style.border=\"2px solid red\"'>";
    echo "<br><small>$url2</small><br>";
    
    // طريقة 3: المسار المباشر
    $url3 = "/sahm/public/storage/$image";
    echo "<p>3. المسار المباشر:</p>";
    echo "<img src='$url3' alt='Test 3' style='max-width: 200px; margin: 5px;' onerror='this.style.border=\"2px solid red\"'>";
    echo "<br><small>$url3</small><br>";
    
    echo "</div>";
}

echo "<h3>فحص روابط الملفات:</h3>";
foreach ($testImages as $image) {
    $filePath = "storage/app/public/$image";
    echo "<p>ملف: $image</p>";
    echo "موجود: " . (file_exists($filePath) ? "✅ نعم" : "❌ لا") . "<br>";
    if (file_exists($filePath)) {
        echo "حجم: " . number_format(filesize($filePath)) . " بايت<br>";
    }
    echo "<hr>";
}

echo "<script>
function addErrorHandling() {
    const images = document.querySelectorAll('img');
    images.forEach(img => {
        img.addEventListener('error', function() {
            this.style.border = '2px solid red';
            this.title = 'فشل في تحميل الصورة';
        });
        
        img.addEventListener('load', function() {
            this.style.border = '2px solid green';
            this.title = 'تم تحميل الصورة بنجاح';
        });
    });
}

addErrorHandling();
</script>";
?> 