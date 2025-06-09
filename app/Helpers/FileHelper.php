<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileHelper
{
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
     * التحقق من نوع الملف
     */
    public static function isImage($filePath)
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg', 'tiff'];
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        return in_array($extension, $imageExtensions);
    }

    /**
     * الحصول على أيقونة الملف حسب نوعه
     */
    public static function getFileIcon($filePath)
    {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        
        $icons = [
            'pdf' => 'fas fa-file-pdf text-danger',
            'doc' => 'fas fa-file-word text-primary',
            'docx' => 'fas fa-file-word text-primary',
            'xls' => 'fas fa-file-excel text-success',
            'xlsx' => 'fas fa-file-excel text-success',
            'ppt' => 'fas fa-file-powerpoint text-warning',
            'pptx' => 'fas fa-file-powerpoint text-warning',
            'zip' => 'fas fa-file-archive text-secondary',
            'rar' => 'fas fa-file-archive text-secondary',
            'txt' => 'fas fa-file-alt text-muted',
        ];

        return $icons[$extension] ?? 'fas fa-file text-muted';
    }

    /**
     * تنسيق حجم الملف
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes === 0) return '0 بايت';

        $units = ['بايت', 'كيلوبايت', 'ميجابايت', 'جيجابايت'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
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