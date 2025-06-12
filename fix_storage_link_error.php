<?php
/**
 * حل مشكلة exec() المعطلة وإنشاء بدائل للرابط الرمزي
 * يعمل في البيئات التي لا تدعم exec() أو symlink()
 */

echo "<!DOCTYPE html>
<html lang='ar' dir='rtl'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>حل مشكلة Storage Link</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .section { background: white; margin: 20px 0; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { color: #28a745; background: #d4edda; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .error { color: #dc3545; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .warning { color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .info { color: #0c5460; background: #d1ecf1; padding: 10px; border-radius: 4px; margin: 10px 0; }
        .code { background: #f8f9fa; padding: 10px; border-radius: 4px; font-family: monospace; border-left: 4px solid #007bff; margin: 10px 0; }
        h1 { color: #333; text-align: center; }
        h2 { color: #007bff; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn.success { background: #28a745; }
        .btn.success:hover { background: #218838; }
    </style>
</head>
<body>";

echo "<h1>🔧 حل مشكلة Storage Link</h1>";

// فحص المشكلة
echo "<div class='section'>";
echo "<h2>🔍 تشخيص المشكلة</h2>";

$problems = [];
$solutions = [];

// فحص دالة exec
if (!function_exists('exec')) {
    $problems[] = "❌ دالة exec() غير متاحة";
    $solutions[] = "استخدام بديل لا يعتمد على exec()";
} else {
    echo "<div class='success'>✅ دالة exec() متاحة</div>";
}

// فحص دالة symlink
if (!function_exists('symlink')) {
    $problems[] = "❌ دالة symlink() غير متاحة";
    $solutions[] = "استخدام نسخ الملفات بدلاً من الروابط الرمزية";
} else {
    echo "<div class='success'>✅ دالة symlink() متاحة</div>";
}

// فحص وجود الرابط
$storagePublicPath = __DIR__ . '/public/storage';
if (is_link($storagePublicPath)) {
    echo "<div class='success'>✅ الرابط الرمزي موجود بالفعل</div>";
} elseif (is_dir($storagePublicPath)) {
    echo "<div class='warning'>⚠️ يوجد مجلد storage بدلاً من رابط رمزي</div>";
    $problems[] = "مجلد storage موجود بدلاً من رابط رمزي";
    $solutions[] = "حذف المجلد وإنشاء رابط أو نسخ";
} else {
    echo "<div class='error'>❌ لا يوجد رابط رمزي أو مجلد storage</div>";
    $problems[] = "لا يوجد وصول لمجلد storage";
    $solutions[] = "إنشاء رابط أو نسخ الملفات";
}

// عرض المشاكل والحلول
if (!empty($problems)) {
    echo "<div class='error'>";
    echo "<h4>المشاكل المكتشفة:</h4>";
    echo "<ul>";
    foreach ($problems as $problem) {
        echo "<li>$problem</li>";
    }
    echo "</ul>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>الحلول المقترحة:</h4>";
    echo "<ul>";
    foreach ($solutions as $solution) {
        echo "<li>$solution</li>";
    }
    echo "</ul>";
    echo "</div>";
}

echo "</div>";

// الحلول المتاحة
echo "<div class='section'>";
echo "<h2>🛠️ الحلول المتاحة</h2>";

echo "<div style='text-align: center;'>";
echo "<a href='?action=copy_files' class='btn'>📁 نسخ الملفات (الحل الأكثر أماناً)</a><br>";
echo "<a href='?action=try_symlink' class='btn'>🔗 محاولة إنشاء رابط رمزي</a><br>";
echo "<a href='?action=create_htaccess' class='btn'>⚙️ إنشاء .htaccess للتوجيه</a><br>";
echo "<a href='?action=manual_instructions' class='btn'>📋 تعليمات يدوية</a>";
echo "</div>";
echo "</div>";

// تنفيذ الحلول
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    
    echo "<div class='section'>";
    
    switch ($action) {
        case 'copy_files':
            echo "<h2>📁 نسخ الملفات</h2>";
            
            $sourceDir = __DIR__ . '/storage/app/public';
            $targetDir = __DIR__ . '/public/storage';
            
            try {
                // حذف المجلد الموجود إذا كان مجلد وليس رابط
                if (is_dir($targetDir) && !is_link($targetDir)) {
                    echo "<div class='warning'>⚠️ حذف المجلد الموجود...</div>";
                    deleteDirectory($targetDir);
                }
                
                // إنشاء المجلد الجديد
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                    echo "<div class='success'>✅ تم إنشاء مجلد storage</div>";
                }
                
                // نسخ الملفات
                $copied = copyDirectory($sourceDir, $targetDir);
                echo "<div class='success'>✅ تم نسخ $copied ملف</div>";
                
                echo "<div class='info'>";
                echo "<h4>📋 ملاحظات مهمة:</h4>";
                echo "<ul>";
                echo "<li>هذا الحل ينسخ الملفات بدلاً من إنشاء رابط رمزي</li>";
                echo "<li>ستحتاج لإعادة النسخ عند إضافة ملفات جديدة</li>";
                echo "<li>يمكنك إنشاء مهمة cron لنسخ الملفات تلقائياً</li>";
                echo "</ul>";
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ خطأ في نسخ الملفات: " . $e->getMessage() . "</div>";
            }
            break;
            
        case 'try_symlink':
            echo "<h2>🔗 محاولة إنشاء رابط رمزي</h2>";
            
            $source = __DIR__ . '/storage/app/public';
            $target = __DIR__ . '/public/storage';
            
            try {
                // حذف الهدف إذا كان موجود
                if (file_exists($target)) {
                    if (is_link($target)) {
                        unlink($target);
                    } elseif (is_dir($target)) {
                        deleteDirectory($target);
                    }
                }
                
                // محاولة إنشاء الرابط الرمزي
                if (function_exists('symlink')) {
                    if (symlink($source, $target)) {
                        echo "<div class='success'>✅ تم إنشاء الرابط الرمزي بنجاح!</div>";
                    } else {
                        throw new Exception("فشل في إنشاء الرابط الرمزي");
                    }
                } else {
                    throw new Exception("دالة symlink() غير متاحة");
                }
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ فشل في إنشاء الرابط الرمزي: " . $e->getMessage() . "</div>";
                echo "<div class='info'>جرب الحل البديل: نسخ الملفات</div>";
            }
            break;
            
        case 'create_htaccess':
            echo "<h2>⚙️ إنشاء .htaccess للتوجيه</h2>";
            
            $htaccessContent = "# توجيه طلبات storage إلى serve_file_enhanced.php
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^storage/(.*)$ serve_file_enhanced.php?file=$1 [QSA,L]

# حماية إضافية
<Files \"serve_file_enhanced.php\">
    Order Allow,Deny
    Allow from all
</Files>";
            
            $htaccessPath = __DIR__ . '/public/.htaccess';
            
            try {
                if (file_exists($htaccessPath)) {
                    $currentContent = file_get_contents($htaccessPath);
                    if (strpos($currentContent, 'serve_file_enhanced.php') === false) {
                        $newContent = $currentContent . "\n\n" . $htaccessContent;
                        file_put_contents($htaccessPath, $newContent);
                        echo "<div class='success'>✅ تم تحديث ملف .htaccess</div>";
                    } else {
                        echo "<div class='info'>ℹ️ ملف .htaccess يحتوي على القواعد بالفعل</div>";
                    }
                } else {
                    file_put_contents($htaccessPath, $htaccessContent);
                    echo "<div class='success'>✅ تم إنشاء ملف .htaccess</div>";
                }
                
                echo "<div class='info'>";
                echo "<h4>📋 تم إضافة القواعد التالية:</h4>";
                echo "<pre>$htaccessContent</pre>";
                echo "</div>";
                
            } catch (Exception $e) {
                echo "<div class='error'>❌ خطأ في إنشاء .htaccess: " . $e->getMessage() . "</div>";
            }
            break;
            
        case 'manual_instructions':
            echo "<h2>📋 تعليمات يدوية</h2>";
            
            echo "<div class='info'>";
            echo "<h4>🔧 إذا كان لديك وصول SSH:</h4>";
            echo "<div class='code'>";
            echo "cd " . __DIR__ . "/public<br>";
            echo "rm -rf storage<br>";
            echo "ln -s " . __DIR__ . "/storage/app/public storage<br>";
            echo "</div>";
            echo "</div>";
            
            echo "<div class='warning'>";
            echo "<h4>📁 إذا لم يكن لديك وصول SSH:</h4>";
            echo "<ol>";
            echo "<li>احذف مجلد <code>public/storage</code> إذا كان موجود</li>";
            echo "<li>انسخ محتويات <code>storage/app/public</code> إلى <code>public/storage</code></li>";
            echo "<li>تأكد من الصلاحيات: <code>chmod 755</code></li>";
            echo "<li>استخدم script تلقائي لنسخ الملفات الجديدة</li>";
            echo "</ol>";
            echo "</div>";
            
            echo "<div class='error'>";
            echo "<h4>⚠️ إذا كانت الاستضافة لا تدعم exec() نهائياً:</h4>";
            echo "<ol>";
            echo "<li>استخدم نسخ الملفات بدلاً من الروابط الرمزية</li>";
            echo "<li>اضبط <code>FILESYSTEM_DISK=local</code> في .env</li>";
            echo "<li>استخدم <code>serve_file_enhanced.php</code> لخدمة الملفات</li>";
            echo "<li>أنشئ مهمة cron لنسخ الملفات الجديدة</li>";
            echo "</ol>";
            echo "</div>";
            break;
    }
    
    echo "</div>";
}

// معلومات إضافية
echo "<div class='section'>";
echo "<h2>💡 معلومات مفيدة</h2>";

echo "<div class='info'>";
echo "<h4>🔍 فحص إعدادات PHP:</h4>";
echo "<ul>";
echo "<li><strong>exec() متاح:</strong> " . (function_exists('exec') ? '✅ نعم' : '❌ لا') . "</li>";
echo "<li><strong>symlink() متاح:</strong> " . (function_exists('symlink') ? '✅ نعم' : '❌ لا') . "</li>";
echo "<li><strong>shell_exec() متاح:</strong> " . (function_exists('shell_exec') ? '✅ نعم' : '❌ لا') . "</li>";
echo "<li><strong>نظام التشغيل:</strong> " . PHP_OS . "</li>";
echo "</ul>";
echo "</div>";

echo "<div class='warning'>";
echo "<h4>⚠️ مشاكل شائعة:</h4>";
echo "<ul>";
echo "<li><strong>exec() معطلة:</strong> شائع في الاستضافات المشتركة لأسباب أمنية</li>";
echo "<li><strong>symlink() معطلة:</strong> بعض الاستضافات تمنع إنشاء الروابط الرمزية</li>";
echo "<li><strong>صلاحيات المجلدات:</strong> تأكد من صلاحيات 755 أو 777</li>";
echo "<li><strong>safe_mode:</strong> إذا كان مفعل قد يمنع بعض العمليات</li>";
echo "</ul>";
echo "</div>";

echo "</div>";

// دوال مساعدة
function deleteDirectory($dir) {
    if (!is_dir($dir)) return false;
    
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $filePath = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath)) {
            deleteDirectory($filePath);
        } else {
            unlink($filePath);
        }
    }
    return rmdir($dir);
}

function copyDirectory($source, $target) {
    $copied = 0;
    
    if (!is_dir($source)) return $copied;
    if (!is_dir($target)) mkdir($target, 0755, true);
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $targetPath = $target . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            if (!is_dir($targetPath)) {
                mkdir($targetPath, 0755, true);
            }
        } else {
            if (copy($item, $targetPath)) {
                $copied++;
            }
        }
    }
    
    return $copied;
}

echo "</body></html>";
?> 