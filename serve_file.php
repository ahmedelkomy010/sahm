<?php
// ملف خدمة الملفات المباشر
// استخدم: /sahm/serve_file.php?file=work_orders/6/survey/1749536914_6847d092c9a78.webp

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
if (!file_exists($filePath)) {
    http_response_code(404);
    exit('File not found');
}

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
header('Cache-Control: public, max-age=31536000'); // كاش لمدة سنة
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT');

// إرسال الملف
readfile($filePath);
exit;
?> 