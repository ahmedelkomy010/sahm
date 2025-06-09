# دليل نشر المشروع على الخادم العالمي

## الإعدادات المطلوبة قبل النشر

### 1. ملف `.env` للإنتاج
```bash
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_username
DB_PASSWORD=your_password

FILESYSTEM_DISK=public
```

### 2. أوامر النشر المطلوبة
```bash
# 1. تحديث الحزم
composer install --optimize-autoloader --no-dev

# 2. تشغيل migrations
php artisan migrate --force

# 3. إنشاء symbolic link للـ storage
php artisan storage:link

# 4. مسح وتحسين الـ cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. تعيين صلاحيات المجلدات
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 755 storage/app/public/uploads/
```

### 3. إعدادات Apache/Nginx

#### Apache (.htaccess في public/)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# إعدادات الأمان للملفات المرفوعة
<Location "/storage/uploads/">
    <Files "*.php">
        Order Deny,Allow
        Deny from all
    </Files>
</Location>
```

#### Nginx
```nginx
location /storage/uploads/ {
    location ~ \.php$ {
        deny all;
    }
    try_files $uri =404;
}
```

### 4. هيكل مجلدات الصور على الخادم العالمي
```
public/
├── storage/
│   └── uploads/
│       └── work_orders/
│           └── {work_order_id}/
│               ├── civil_works_execution/     # صور الأعمال المدنية
│               └── civil_works_attachments/   # مرفقات الأعمال المدنية
```

### 5. التأكد من عمل رفع الصور
- تأكد من وجود مجلد `storage/app/public/uploads/`
- تأكد من صلاحيات الكتابة على المجلدات
- تأكد من عمل symbolic link: `public/storage -> storage/app/public`

### 6. إعدادات قاعدة البيانات
تأكد من أن عمود `file_category` في جدول `work_order_files` يدعم حتى 100 حرف:
```sql
ALTER TABLE work_order_files MODIFY file_category VARCHAR(100);
```

### 7. اختبار النظام
1. ادخل لصفحة الأعمال المدنية
2. ارفع بعض الصور
3. اضغط "حفظ الصور"
4. اذهب لصفحة "إجراءات ما بعد التنفيذ"
5. تأكد من ظهور الصور في قسم "صور التنفيذ - الأعمال المدنية"

## استكشاف الأخطاء

### خطأ 500 عند رفع الصور
- تأكد من صلاحيات مجلد storage
- تأكد من إعداد .env صحيح
- تحقق من logs في `storage/logs/`

### الصور لا تظهر
- تأكد من symbolic link: `php artisan storage:link`
- تأكد من مسار APP_URL في .env
- تحقق من إعدادات web server

### خطأ Database
- تأكد من تشغيل migration الأخيرة
- تحقق من حجم عمود file_category في قاعدة البيانات 