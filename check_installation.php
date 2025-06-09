<?php
/**
 * سكريپت فحص تثبيت نظام SAHM
 * يجب تشغيله بعد التثبيت للتأكد من سلامة النظام
 */

echo "🔍 فحص تثبيت نظام SAHM...\n\n";

$errors = [];
$warnings = [];
$success = [];

// فحص إصدار PHP
echo "📋 فحص إصدار PHP...\n";
if (version_compare(PHP_VERSION, '8.1.0', '>=')) {
    $success[] = "✅ إصدار PHP صحيح: " . PHP_VERSION;
} else {
    $errors[] = "❌ إصدار PHP قديم: " . PHP_VERSION . " (مطلوب 8.1 أو أحدث)";
}

// فحص الـ Extensions
echo "📋 فحص PHP Extensions...\n";
$required_extensions = [
    'pdo_mysql', 'openssl', 'mbstring', 'tokenizer', 
    'xml', 'ctype', 'json', 'bcmath', 'fileinfo', 'gd'
];

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "✅ Extension $ext موجود";
    } else {
        $errors[] = "❌ Extension $ext مفقود";
    }
}

// فحص ملف .env
echo "📋 فحص ملف .env...\n";
if (file_exists('.env')) {
    $success[] = "✅ ملف .env موجود";
    
    // قراءة محتوى .env
    $env_content = file_get_contents('.env');
    
    // فحص المتغيرات المهمة
    $required_vars = ['APP_KEY', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD'];
    foreach ($required_vars as $var) {
        if (strpos($env_content, $var . '=') !== false) {
            $success[] = "✅ متغير $var موجود";
        } else {
            $errors[] = "❌ متغير $var مفقود في .env";
        }
    }
    
    // فحص APP_KEY
    if (strpos($env_content, 'APP_KEY=base64:') !== false && 
        !strpos($env_content, 'APP_KEY=base64:YOUR_APP_KEY_HERE')) {
        $success[] = "✅ APP_KEY تم تعيينه";
    } else {
        $errors[] = "❌ APP_KEY لم يتم تعيينه - شغل php artisan key:generate";
    }
    
} else {
    $errors[] = "❌ ملف .env مفقود";
}

// فحص أذونات المجلدات
echo "📋 فحص أذونات المجلدات...\n";
$writable_dirs = ['storage', 'storage/app', 'storage/framework', 'storage/logs', 'bootstrap/cache'];

foreach ($writable_dirs as $dir) {
    if (is_dir($dir) && is_writable($dir)) {
        $success[] = "✅ مجلد $dir قابل للكتابة";
    } else {
        $errors[] = "❌ مجلد $dir غير قابل للكتابة";
    }
}

// فحص اتصال قاعدة البيانات
echo "📋 فحص اتصال قاعدة البيانات...\n";
try {
    require_once 'vendor/autoload.php';
    
    // تحميل متغيرات البيئة
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
    
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";dbname=" . $_ENV['DB_DATABASE'];
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $success[] = "✅ اتصال قاعدة البيانات يعمل";
    
    // فحص الجداول
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $required_tables = ['users', 'work_orders', 'materials', 'work_order_files'];
    $missing_tables = array_diff($required_tables, $tables);
    
    if (empty($missing_tables)) {
        $success[] = "✅ جميع الجداول موجودة";
    } else {
        $errors[] = "❌ جداول مفقودة: " . implode(', ', $missing_tables);
        $errors[] = "تشغيل: php artisan migrate";
    }
    
} catch (Exception $e) {
    $errors[] = "❌ خطأ في اتصال قاعدة البيانات: " . $e->getMessage();
}

// فحص مجلد التحميلات
echo "📋 فحص مجلد التحميلات...\n";
if (is_dir('public/uploads')) {
    if (is_writable('public/uploads')) {
        $success[] = "✅ مجلد uploads قابل للكتابة";
    } else {
        $warnings[] = "⚠️ مجلد uploads غير قابل للكتابة";
    }
} else {
    $warnings[] = "⚠️ مجلد uploads غير موجود";
}

// فحص symbolic link
if (is_link('public/storage')) {
    $success[] = "✅ symbolic link للـ storage موجود";
} else {
    $warnings[] = "⚠️ symbolic link للـ storage مفقود - شغل php artisan storage:link";
}

// طباعة النتائج
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 تقرير الفحص النهائي\n";
echo str_repeat("=", 50) . "\n\n";

if (!empty($success)) {
    echo "✅ العناصر السليمة:\n";
    foreach ($success as $item) {
        echo "  $item\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠️ تحذيرات:\n";
    foreach ($warnings as $item) {
        echo "  $item\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "❌ أخطاء تحتاج إصلاح:\n";
    foreach ($errors as $item) {
        echo "  $item\n";
    }
    echo "\n";
    echo "🔧 يجب إصلاح الأخطاء أعلاه قبل تشغيل النظام\n\n";
} else {
    echo "🎉 تهانينا! النظام جاهز للعمل\n";
    echo "🌐 يمكنك الآن الوصول للنظام عبر المتصفح\n\n";
}

// معلومات إضافية
echo "📝 معلومات إضافية:\n";
echo "  - إصدار PHP: " . PHP_VERSION . "\n";
echo "  - مساحة الذاكرة: " . ini_get('memory_limit') . "\n";
echo "  - حد رفع الملفات: " . ini_get('upload_max_filesize') . "\n";
echo "  - حد POST: " . ini_get('post_max_size') . "\n";
echo "\n";

echo "✅ انتهى الفحص\n";
?> 