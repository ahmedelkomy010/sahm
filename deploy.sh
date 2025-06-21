#!/bin/bash
# سكريبت نشر نظام SAHM على cPanel

echo "🚀 بدء نشر نظام SAHM..."

# التأكد من وجود Composer2
if ! command -v composer2 &> /dev/null; then
    echo "⚠️ Composer2 غير موجود. جاري استخدام composer..."
    COMPOSER_CMD="composer"
else
    COMPOSER_CMD="composer2"
fi

# تثبيت التبعيات باستخدام Composer2
echo "📦 تثبيت التبعيات..."
$COMPOSER_CMD install --no-dev --optimize-autoloader

# تنظيف المخزن المؤقت
echo "🧹 تنظيف المخزن المؤقت..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# تشغيل قاعدة البيانات
echo "🗄️ تشغيل قاعدة البيانات..."
php artisan migrate --force

# إنشاء الروابط الرمزية
echo "🔗 إنشاء الروابط الرمزية..."
php artisan storage:link

# تحسين الأداء
echo "⚡ تحسين الأداء..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# ضبط الأذونات
echo "🔐 ضبط الأذونات..."
find storage -type d -exec chmod 755 {} \;
find storage -type f -exec chmod 644 {} \;
find bootstrap/cache -type d -exec chmod 755 {} \;
find bootstrap/cache -type f -exec chmod 644 {} \;
find public -type d -exec chmod 755 {} \;
find public -type f -exec chmod 644 {} \;

# فحص التثبيت
echo "🔍 فحص التثبيت..."
php check_installation.php

# تنظيف ذاكرة التخزين المؤقت لـ Composer
echo "🧹 تنظيف ذاكرة التخزين المؤقت لـ Composer..."
$COMPOSER_CMD clear-cache

echo "✅ انتهى النشر بنجاح!"
echo "🌐 يمكنك الآن الوصول للنظام" 