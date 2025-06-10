<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class FileHelper
{
    /**
     * إنتاج رابط آمن للملف
     */
    public static function getFileUrl($filePath)
    {
        if (!$filePath) {
            return null;
        }

        // إذا كان المسار يبدأ بـ http أو https، أرجعه كما هو
        if (str_starts_with($filePath, 'http://') || str_starts_with($filePath, 'https://')) {
            return $filePath;
        }

        // إذا كان المسار يبدأ بـ storage/, أرجع الرابط المباشر
        if (str_starts_with($filePath, 'storage/')) {
            return asset($filePath);
        }

        // إذا كان المسار عادي، استخدم Storage::url
        try {
            $url = Storage::url($filePath);
            
            // فحص إمكانية الوصول للرابط
            $headers = @get_headers($url, 1);
            if (!$headers || strpos($headers[0], '404') !== false) {
                // إذا فشل الرابط العادي، استخدم المسار البديل
                return route('files.serve', ['path' => $filePath]);
            }
            
            return $url;
        } catch (\Exception $e) {
            // في حالة الخطأ، استخدم المسار البديل
            return route('files.serve', ['path' => $filePath]);
        }
    }

    /**
     * التحقق من وجود الملف
     */
    public static function fileExists($filePath)
    {
        if (!$filePath) {
            return false;
        }

        return Storage::disk('public')->exists($filePath);
    }

    /**
     * الحصول على حجم الملف
     */
    public static function getFileSize($filePath)
    {
        if (!$filePath || !self::fileExists($filePath)) {
            return 0;
        }

        return Storage::disk('public')->size($filePath);
    }

    /**
     * تنسيق حجم الملف للعرض
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * التحقق من نوع الملف
     */
    public static function isImage($filePath)
    {
        if (!$filePath) {
            return false;
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    /**
     * الحصول على أيقونة الملف حسب النوع
     */
    public static function getFileIcon($filePath)
    {
        if (!$filePath) {
            return 'fas fa-file';
        }

        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'pdf':
                return 'fas fa-file-pdf text-danger';
            case 'doc':
            case 'docx':
                return 'fas fa-file-word text-primary';
            case 'xls':
            case 'xlsx':
                return 'fas fa-file-excel text-success';
            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
            case 'webp':
                return 'fas fa-file-image text-info';
            case 'zip':
            case 'rar':
                return 'fas fa-file-archive text-warning';
            default:
                return 'fas fa-file text-secondary';
        }
    }

    /**
     * الحصول على رابط آمن للصورة مع fallback
     */
    public static function getImageUrl($filePath, $default = null)
    {
        if (empty($filePath)) {
            return $default ?: asset('images/no-image.png');
        }

        // تنظيف المسار
        $cleanPath = ltrim($filePath, '/');
        
        // التحقق من وجود الملف
        if (Storage::disk('public')->exists($cleanPath)) {
            return asset('storage/' . $cleanPath);
        }

        // البحث عن الملف في مسارات مختلفة
        $possiblePaths = [
            $cleanPath,
            'work_orders/' . $cleanPath,
            'uploads/' . $cleanPath,
        ];

        foreach ($possiblePaths as $path) {
            if (Storage::disk('public')->exists($path)) {
                return asset('storage/' . $path);
            }
        }

        return $default ?: asset('images/no-image.png');
    }

    /**
     * إنشاء thumbnail للصورة
     */
    public static function getThumbnailUrl($filePath, $width = 150, $height = 150)
    {
        if (!self::isImage($filePath)) {
            return null;
        }

        // يمكن تطوير هذه الوظيفة لاحقاً لإنشاء thumbnails فعلية
        return self::getImageUrl($filePath);
    }

    /**
     * التحقق من صحة امتداد الملف
     */
    public static function isValidImageExtension($filename)
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'tiff'];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        return in_array($extension, $allowedExtensions);
    }

    /**
     * تنظيف اسم الملف
     */
    public static function sanitizeFilename($filename)
    {
        // إزالة الأحرف الغير مرغوب فيها
        $filename = preg_replace('/[^a-zA-Z0-9\._-]/', '', $filename);
        
        // تحديد الطول
        if (strlen($filename) > 100) {
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $name = pathinfo($filename, PATHINFO_FILENAME);
            $filename = substr($name, 0, 100 - strlen($extension) - 1) . '.' . $extension;
        }

        return $filename;
    }
} 