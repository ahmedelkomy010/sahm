<?php
/**
 * سكريپت النسخ الاحتياطي لنظام SAHM
 * يقوم بعمل نسخة احتياطية من قاعدة البيانات والملفات
 */

require_once 'vendor/autoload.php';

// تحميل متغيرات البيئة
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "🔄 بدء عملية النسخ الاحتياطي...\n";

$date = date('Y-m-d_H-i-s');
$backup_dir = "backups/{$date}";

// إنشاء مجلد النسخ الاحتياطي
if (!is_dir('backups')) {
    mkdir('backups', 0755, true);
}
mkdir($backup_dir, 0755, true);

echo "📁 إنشاء مجلد النسخ الاحتياطي: {$backup_dir}\n";

// نسخ احتياطية لقاعدة البيانات
echo "🗄️ عمل نسخة احتياطية لقاعدة البيانات...\n";

$db_host = $_ENV['DB_HOST'];
$db_name = $_ENV['DB_DATABASE'];
$db_user = $_ENV['DB_USERNAME'];
$db_pass = $_ENV['DB_PASSWORD'];

$backup_file = "{$backup_dir}/database_backup.sql";

// أمر mysqldump
$command = "mysqldump --host={$db_host} --user={$db_user} --password={$db_pass} {$db_name} > {$backup_file}";

exec($command, $output, $return_code);

if ($return_code === 0) {
    echo "✅ تم إنشاء نسخة احتياطية من قاعدة البيانات\n";
} else {
    echo "❌ فشل في إنشاء نسخة احتياطية من قاعدة البيانات\n";
    exit(1);
}

// نسخ احتياطية للملفات المهمة
echo "📄 عمل نسخة احتياطية للملفات...\n";

$important_files = [
    '.env' => '.env',
    'public/uploads' => 'uploads',
    'storage/app' => 'storage_app',
];

foreach ($important_files as $source => $destination) {
    if (file_exists($source)) {
        $dest_path = "{$backup_dir}/{$destination}";
        
        if (is_dir($source)) {
            // نسخ مجلد
            $command = "cp -r {$source} {$dest_path}";
            exec($command);
            echo "✅ تم نسخ مجلد: {$source}\n";
        } else {
            // نسخ ملف
            copy($source, $dest_path);
            echo "✅ تم نسخ ملف: {$source}\n";
        }
    } else {
        echo "⚠️ الملف غير موجود: {$source}\n";
    }
}

// إنشاء ملف معلومات النسخة الاحتياطية
$info = [
    'backup_date' => date('Y-m-d H:i:s'),
    'system_version' => 'SAHM v1.0',
    'php_version' => PHP_VERSION,
    'database_name' => $db_name,
    'files_included' => array_keys($important_files),
];

file_put_contents("{$backup_dir}/backup_info.json", json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// ضغط النسخة الاحتياطية
echo "🗜️ ضغط النسخة الاحتياطية...\n";

$zip_file = "backups/sahm_backup_{$date}.zip";
$zip = new ZipArchive();

if ($zip->open($zip_file, ZipArchive::CREATE) === TRUE) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($backup_dir),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    foreach ($iterator as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($backup_dir) + 1);
            $zip->addFile($filePath, $relativePath);
        }
    }

    $zip->close();
    echo "✅ تم إنشاء ملف مضغوط: {$zip_file}\n";

    // حذف المجلد المؤقت
    $command = "rm -rf {$backup_dir}";
    exec($command);
    echo "🧹 تم تنظيف الملفات المؤقتة\n";
} else {
    echo "❌ فشل في إنشاء الملف المضغوط\n";
}

// تنظيف النسخ القديمة (الاحتفاظ بآخر 7 نسخ)
echo "🧹 تنظيف النسخ القديمة...\n";

$backup_files = glob('backups/sahm_backup_*.zip');
rsort($backup_files); // ترتيب من الأحدث للأقدم

if (count($backup_files) > 7) {
    $old_backups = array_slice($backup_files, 7);
    foreach ($old_backups as $old_backup) {
        unlink($old_backup);
        echo "🗑️ تم حذف النسخة القديمة: " . basename($old_backup) . "\n";
    }
}

echo "\n✅ انتهت عملية النسخ الاحتياطي بنجاح!\n";
echo "📦 ملف النسخة الاحتياطية: {$zip_file}\n";
echo "📊 حجم الملف: " . round(filesize($zip_file) / 1024 / 1024, 2) . " ميجابايت\n";

?> 