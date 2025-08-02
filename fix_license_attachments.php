<?php

// require_once __DIR__ . '/vendor/autoload.php';

// use Illuminate\Foundation\Application;
// use Illuminate\Support\Facades\Storage;
// use App\Models\License;

// // تشغيل Laravel bootstrap
// $app = require_once __DIR__ . '/bootstrap/app.php';
// $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
// $kernel->bootstrap();

// echo "=== بدء إصلاح مشاكل مرفقات الرخص ===\n\n";

// // 1. التحقق من إعدادات Storage
// echo "1. التحقق من إعدادات التخزين:\n";
// try {
//     $publicDisk = Storage::disk('public');
//     echo "   - Public disk متاح: ✓\n";
    
//     // التحقق من الروابط الرمزية
//     $publicPath = public_path('storage');
//     if (is_link($publicPath)) {
//         echo "   - Storage link موجود: ✓\n";
//     } else {
//         echo "   - Storage link غير موجود: ✗\n";
//         echo "     تشغيل: php artisan storage:link\n";
//     }
    
// } catch (Exception $e) {
//     echo "   - خطأ في إعدادات التخزين: " . $e->getMessage() . "\n";
// }

// // 2. إنشاء مجلدات التخزين المطلوبة
// echo "\n2. إنشاء مجلدات التخزين:\n";
// $directories = [
//     'licenses/coordination',
//     'licenses/letters',
//     'licenses/files',
//     'licenses/invoices',
//     'licenses/proof',
//     'licenses/activation',
//     'licenses/notes',
//     'licenses/lab'
// ];

// foreach ($directories as $directory) {
//     try {
//         if (!Storage::disk('public')->exists($directory)) {
//             Storage::disk('public')->makeDirectory($directory);
//             echo "   - تم إنشاء مجلد: {$directory} ✓\n";
//         } else {
//             echo "   - مجلد موجود: {$directory} ✓\n";
//         }
//     } catch (Exception $e) {
//         echo "   - خطأ في إنشاء مجلد {$directory}: " . $e->getMessage() . "\n";
//     }
// }

// // 3. التحقق من الرخص الموجودة ومرفقاتها
// echo "\n3. التحقق من الرخص الموجودة:\n";
// try {
//     $licenses = License::whereNotNull('coordination_certificate_path')
//                       ->orWhereNotNull('letters_commitments_file_path')
//                       ->orWhereNotNull('license_file_path')
//                       ->orWhereNotNull('payment_invoices_path')
//                       ->orWhereNotNull('payment_proof_path')
//                       ->get();
    
//     echo "   - عدد الرخص التي تحتوي على مرفقات: " . $licenses->count() . "\n";
    
//     $missingFiles = 0;
//     $validFiles = 0;
    
//     foreach ($licenses as $license) {
//         // فحص ملف شهادة التنسيق
//         if ($license->coordination_certificate_path) {
//             if (Storage::disk('public')->exists($license->coordination_certificate_path)) {
//                 $validFiles++;
//             } else {
//                 $missingFiles++;
//                 echo "     - ملف شهادة التنسيق مفقود للرخصة #{$license->id}\n";
//             }
//         }
        
//         // فحص ملفات الخطابات والتعهدات
//         if ($license->letters_commitments_file_path) {
//             $files = $license->getMultipleFiles('letters_commitments_file_path');
//             foreach ($files as $file) {
//                 if (Storage::disk('public')->exists($file)) {
//                     $validFiles++;
//                 } else {
//                     $missingFiles++;
//                     echo "     - ملف خطابات مفقود للرخصة #{$license->id}: {$file}\n";
//                 }
//             }
//         }
        
//         // فحص ملف الرخصة
//         if ($license->license_file_path) {
//             if (Storage::disk('public')->exists($license->license_file_path)) {
//                 $validFiles++;
//             } else {
//                 $missingFiles++;
//                 echo "     - ملف الرخصة مفقود للرخصة #{$license->id}\n";
//             }
//         }
        
//         // فحص إيصالات الدفع
//         if ($license->payment_invoices_path) {
//             $files = $license->getMultipleFiles('payment_invoices_path');
//             foreach ($files as $file) {
//                 if (Storage::disk('public')->exists($file)) {
//                     $validFiles++;
//                 } else {
//                     $missingFiles++;
//                     echo "     - إيصال دفع مفقود للرخصة #{$license->id}: {$file}\n";
//                 }
//             }
//         }
        
//         // فحص إثباتات الدفع
//         if ($license->payment_proof_path) {
//             $files = $license->getMultipleFiles('payment_proof_path');
//             foreach ($files as $file) {
//                 if (Storage::disk('public')->exists($file)) {
//                     $validFiles++;
//                 } else {
//                     $missingFiles++;
//                     echo "     - إثبات دفع مفقود للرخصة #{$license->id}: {$file}\n";
//                 }
//             }
//         }
//     }
    
//     echo "   - الملفات الصحيحة: {$validFiles}\n";
//     echo "   - الملفات المفقودة: {$missingFiles}\n";
    
// } catch (Exception $e) {
//     echo "   - خطأ في فحص الرخص: " . $e->getMessage() . "\n";
// }

// // 4. التحقق من الأذونات
// echo "\n4. التحقق من أذونات المجلدات:\n";
// $storagePath = storage_path('app/public');
// if (is_writable($storagePath)) {
//     echo "   - مجلد التخزين قابل للكتابة: ✓\n";
// } else {
//     echo "   - مجلد التخزين غير قابل للكتابة: ✗\n";
//     echo "     تشغيل: chmod -R 755 {$storagePath}\n";
// }

// echo "\n=== انتهى إصلاح مشاكل مرفقات الرخص ===\n";
// echo "يُنصح بتشغيل الأوامر التالية إذا لم تكن تعمل:\n";
// echo "php artisan storage:link\n";
// echo "php artisan migrate\n";
// echo "chmod -R 755 storage/\n";
// echo "chmod -R 755 public/storage/\n"; 