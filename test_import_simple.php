<?php

// اختبار بسيط للاستيراد
require_once 'vendor/autoload.php';

// التحقق من وجود المكتبة
if (class_exists('\Maatwebsite\Excel\Facades\Excel')) {
    echo "✅ مكتبة Excel متاحة\n";
} else {
    echo "❌ مكتبة Excel غير متاحة\n";
}

// التحقق من وجود كلاس الاستيراد
if (class_exists('\App\Imports\WorkItemsImport')) {
    echo "✅ كلاس WorkItemsImport متاح\n";
} else {
    echo "❌ كلاس WorkItemsImport غير متاح\n";
}

// التحقق من وجود نموذج WorkItem
if (class_exists('\App\Models\WorkItem')) {
    echo "✅ نموذج WorkItem متاح\n";
} else {
    echo "❌ نموذج WorkItem غير متاح\n";
}

echo "تم الانتهاء من الاختبار\n"; 