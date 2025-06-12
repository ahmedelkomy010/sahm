<?php
echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>فحص دوال PHP</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .section { background: white; margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .warning { color: #ffc107; }
        .info { color: #17a2b8; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background: #f8f9fa; font-weight: bold; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
    </style>
</head>
<body>";

echo "<h1>🔍 فحص دوال PHP</h1>";

// معلومات PHP العامة
echo "<div class='section'>";
echo "<h2>📊 معلومات PHP</h2>";
echo "<table>";
echo "<tr><th>المعلومة</th><th>القيمة</th></tr>";
echo "<tr><td>إصدار PHP</td><td>" . PHP_VERSION . "</td></tr>";
echo "<tr><td>نظام التشغيل</td><td>" . PHP_OS . "</td></tr>";
echo "<tr><td>ملف php.ini</td><td>" . php_ini_loaded_file() . "</td></tr>";
echo "<tr><td>مجلد الامتدادات</td><td>" . ini_get('extension_dir') . "</td></tr>";
echo "</table>";
echo "</div>";

// فحص الدوال الحساسة
echo "<div class='section'>";
echo "<h2>🔧 فحص الدوال الحساسة</h2>";

$sensitiveFunctions = [
    'exec' => 'تنفيذ أوامر النظام',
    'shell_exec' => 'تنفيذ أوامر shell',
    'system' => 'تنفيذ أوامر النظام',
    'passthru' => 'تنفيذ أوامر خارجية',
    'symlink' => 'إنشاء روابط رمزية',
    'file_get_contents' => 'قراءة الملفات',
    'file_put_contents' => 'كتابة الملفات',
    'fopen' => 'فتح الملفات',
    'curl_exec' => 'HTTP requests'
];

echo "<table>";
echo "<tr><th>الدالة</th><th>الوصف</th><th>الحالة</th></tr>";

foreach ($sensitiveFunctions as $function => $description) {
    $available = function_exists($function);
    $status = $available ? "<span class='success'>✅ متاحة</span>" : "<span class='error'>❌ غير متاحة</span>";
    echo "<tr><td><code>$function()</code></td><td>$description</td><td>$status</td></tr>";
}
echo "</table>";
echo "</div>";

// فحص disable_functions
echo "<div class='section'>";
echo "<h2>🚫 الدوال المعطلة (disable_functions)</h2>";

$disabledFunctions = ini_get('disable_functions');
if (empty($disabledFunctions)) {
    echo "<div class='success'>✅ لا توجد دوال معطلة في disable_functions</div>";
} else {
    echo "<div class='warning'>⚠️ الدوال المعطلة:</div>";
    echo "<div class='code'>$disabledFunctions</div>";
    
    $disabledArray = array_map('trim', explode(',', $disabledFunctions));
    echo "<table>";
    echo "<tr><th>الدالة المعطلة</th></tr>";
    foreach ($disabledArray as $func) {
        if (!empty($func)) {
            echo "<tr><td><code>$func()</code></td></tr>";
        }
    }
    echo "</table>";
}
echo "</div>";

// فحص إعدادات أخرى مهمة
echo "<div class='section'>";
echo "<h2>⚙️ إعدادات أمنية أخرى</h2>";

$securitySettings = [
    'safe_mode' => 'الوضع الآمن (مهجور)',
    'open_basedir' => 'تقييد مجلدات الوصول',
    'allow_url_fopen' => 'السماح بفتح URLs',
    'allow_url_include' => 'السماح بتضمين URLs',
    'display_errors' => 'عرض الأخطاء',
    'log_errors' => 'تسجيل الأخطاء',
    'expose_php' => 'كشف معلومات PHP'
];

echo "<table>";
echo "<tr><th>الإعداد</th><th>القيمة</th><th>الوصف</th></tr>";

foreach ($securitySettings as $setting => $description) {
    $value = ini_get($setting);
    $displayValue = $value ? ($value === '1' ? 'مفعل' : $value) : 'معطل';
    echo "<tr><td><code>$setting</code></td><td>$displayValue</td><td>$description</td></tr>";
}
echo "</table>";
echo "</div>";

// اختبار exec()
echo "<div class='section'>";
echo "<h2>🧪 اختبار دالة exec()</h2>";

if (function_exists('exec')) {
    echo "<div class='success'>✅ دالة exec() متاحة</div>";
    
    // اختبار بسيط
    $output = array();
    $return_var = 0;
    
    if (PHP_OS_FAMILY === 'Windows') {
        $command = 'echo "Test Command"';
        exec($command, $output, $return_var);
    } else {
        $command = 'echo "Test Command"';
        exec($command, $output, $return_var);
    }
    
    if ($return_var === 0) {
        echo "<div class='success'>✅ تم تنفيذ الأمر بنجاح</div>";
        echo "<div class='code'>الأمر: $command<br>النتيجة: " . implode('<br>', $output) . "</div>";
    } else {
        echo "<div class='error'>❌ فشل في تنفيذ الأمر</div>";
        echo "<div class='code'>كود الخطأ: $return_var</div>";
    }
} else {
    echo "<div class='error'>❌ دالة exec() غير متاحة</div>";
}
echo "</div>";

// اختبار Laravel storage:link
echo "<div class='section'>";
echo "<h2>🔗 اختبار Laravel storage:link</h2>";

if (function_exists('exec')) {
    echo "<div class='info'>جرب تشغيل الأمر التالي:</div>";
    echo "<div class='code'>php artisan storage:link</div>";
    
    echo "<div style='margin: 20px 0;'>";
    echo "<a href='?test_storage_link=1' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;'>اختبار storage:link</a>";
    echo "</div>";
    
    if (isset($_GET['test_storage_link'])) {
        echo "<div class='warning'>⚠️ محاولة تشغيل storage:link...</div>";
        
        $output = array();
        $return_var = 0;
        
        // تغيير المجلد إلى مجلد Laravel
        $laravelPath = dirname(__FILE__);
        $command = "cd \"$laravelPath\" && php artisan storage:link 2>&1";
        
        exec($command, $output, $return_var);
        
        if ($return_var === 0) {
            echo "<div class='success'>✅ تم تنفيذ storage:link بنجاح!</div>";
        } else {
            echo "<div class='error'>❌ فشل في تنفيذ storage:link</div>";
        }
        
        echo "<div class='code'>";
        echo "الأمر: $command<br>";
        echo "كود الإرجاع: $return_var<br>";
        echo "النتيجة:<br>" . implode('<br>', $output);
        echo "</div>";
    }
} else {
    echo "<div class='error'>❌ لا يمكن اختبار storage:link لأن exec() غير متاحة</div>";
}
echo "</div>";

// توصيات
echo "<div class='section'>";
echo "<h2>💡 التوصيات</h2>";

if (function_exists('exec')) {
    echo "<div class='success'>";
    echo "<h4>✅ exec() متاحة - يمكنك:</h4>";
    echo "<ul>";
    echo "<li>تشغيل <code>php artisan storage:link</code> بنجاح</li>";
    echo "<li>استخدام Laravel في البيئة الإنتاجية بدون مشاكل</li>";
    echo "<li>تنفيذ scripts الصيانة التلقائية</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div class='warning'>";
    echo "<h4>⚠️ exec() غير متاحة - الحلول البديلة:</h4>";
    echo "<ul>";
    echo "<li>استخدم <code>fix_storage_link_error.php</code> لنسخ الملفات</li>";
    echo "<li>اتصل بدعم الاستضافة لتمكين exec()</li>";
    echo "<li>استخدم serve_file_enhanced.php لخدمة الملفات</li>";
    echo "</ul>";
    echo "</div>";
}

echo "</div>";

echo "</body></html>";
?> 