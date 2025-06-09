#!/bin/bash
# سكريبت نشر نظام SAHM على cPanel

echo "🚀 بدء نشر نظام SAHM..."

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
chmod 755 storage/
chmod 755 storage/app/
chmod 755 storage/framework/
chmod 755 storage/logs/
chmod 755 bootstrap/cache/
chmod 755 public/
chmod 755 public/uploads/

# فحص التثبيت
echo "🔍 فحص التثبيت..."
php check_installation.php

echo "✅ انتهى النشر بنجاح!"
echo "🌐 يمكنك الآن الوصول للنظام" 