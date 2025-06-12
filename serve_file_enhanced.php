<?php
// ملف خدمة الملفات المحسن - يدعم عرض صور بديلة للملفات المفقودة
// استخدم: /sahm/serve_file_enhanced.php?file=work_orders/6/survey/missing_file.png

if (!isset($_GET['file'])) {
    http_response_code(400);
    exit('File parameter required');
}

$requestedFile = $_GET['file'];

// تنظيف المسار من أي محاولات اختراق
$requestedFile = str_replace(['../', '..\\', '//', '\\\\'], '', $requestedFile);

// المسار الكامل للملف
$filePath = __DIR__ . '/storage/app/public/' . $requestedFile;

// التحقق من وجود الملف
if (file_exists($filePath)) {
    // الملف موجود - إرسال الملف الأصلي
    serveFile($filePath);
} else {
    // الملف غير موجود - البحث عن بدائل
    $alternativeFile = findAlternativeFile($requestedFile);
    
    if ($alternativeFile && file_exists($alternativeFile)) {
        serveFile($alternativeFile);
    } else {
        // لا توجد بدائل - عرض صورة بديلة
        serveDefaultImage();
    }
}

function findAlternativeFile($requestedFile) {
    $directory = dirname($requestedFile);
    $filename = basename($requestedFile);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    
    // إذا كان الملف المطلوب PNG، ابحث عن WEBP بدلاً منه
    if (strtolower($extension) === 'png') {
        $webpFile = __DIR__ . '/storage/app/public/' . $directory . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
        if (file_exists($webpFile)) {
            return $webpFile;
        }
    }
    
    // البحث في مجلد الملف عن أي صورة مشابهة
    $fullDirectory = __DIR__ . '/storage/app/public/' . $directory;
    if (is_dir($fullDirectory)) {
        $files = scandir($fullDirectory);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..' && is_file($fullDirectory . '/' . $file)) {
                $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return $fullDirectory . '/' . $file;
                }
            }
        }
    }
    
    return null;
}

function serveFile($filePath) {
    // التحقق من أن الملف من النوع المسموح
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx'];
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions)) {
        http_response_code(403);
        exit('File type not allowed');
    }

    // تحديد نوع المحتوى
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';

    // إرسال headers
    header('Content-Type: ' . $mimeType);
    header('Content-Length: ' . filesize($filePath));
    header('Cache-Control: public, max-age=31536000');
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');

    // إرسال الملف
    readfile($filePath);
    exit;
}

function serveDefaultImage() {
    // إنشاء صورة بديلة بسيطة
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=3600');
    
    // صورة PNG بسيطة (1x1 pixel شفاف)
    $pngData = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==');
    
    // أو يمكن إنشاء صورة مع نص
    $image = imagecreate(200, 100);
    $bg_color = imagecolorallocate($image, 240, 240, 240);
    $text_color = imagecolorallocate($image, 100, 100, 100);
    
    imagestring($image, 3, 50, 40, 'Image Not Found', $text_color);
    
    ob_start();
    imagepng($image);
    $imageData = ob_get_contents();
    ob_end_clean();
    
    imagedestroy($image);
    
    echo $imageData;
    exit;
}
?> 