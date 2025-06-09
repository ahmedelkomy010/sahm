# 🚀 دليل تثبيت نظام إدارة أوامر العمل - SAHM

## متطلبات الاستضافة

### الحد الأدنى للمتطلبات:
- **PHP**: 8.1 أو أحدث
- **MySQL**: 5.7 أو أحدث  
- **مساحة تخزين**: 500 ميجابايت على الأقل
- **ذاكرة**: 256 ميجابايت على الأقل
- **Extensions المطلوبة**: 
  - PDO MySQL
  - OpenSSL
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD Library

---

## خطوات التثبيت على cPanel

### 1️⃣ تحضير الملفات

1. **رفع الملفات**:
   ```bash
   # ارفع جميع ملفات المشروع إلى مجلد مؤقت (مثل /tmp/sahm)
   # لا ترفعها مباشرة إلى public_html
   ```

2. **نقل محتويات public**:
   ```bash
   # انقل محتويات مجلد public إلى public_html
   # انقل باقي الملفات إلى مجلد خارج public_html (مثل /private_html/sahm)
   ```

### 2️⃣ إعداد قاعدة البيانات

1. **إنشاء قاعدة بيانات جديدة من cPanel**
2. **إنشاء مستخدم قاعدة بيانات**
3. **ربط المستخدم بقاعدة البيانات مع جميع الصلاحيات**

### 3️⃣ تكوين ملف البيئة

1. **انسخ `config/production.env` إلى `.env`**
2. **عدل الإعدادات التالية**:

```env
# بيانات موقعك
APP_NAME="اسم مشروعك"
APP_URL=https://yourdomain.com

# بيانات قاعدة البيانات من cPanel
DB_DATABASE=اسم_قاعدة_البيانات_من_سي_بانل
DB_USERNAME=اسم_المستخدم_من_سي_بانل  
DB_PASSWORD=كلمة_المرور_من_سي_بانل

# بيانات البريد الإلكتروني (اختياري)
MAIL_HOST=mail.yourdomain.com
MAIL_USERNAME=noreply@yourdomain.com
MAIL_PASSWORD=كلمة_مرور_البريد
```

### 4️⃣ تعديل مسارات التطبيق

1. **في الملف `public/index.php`**:
   ```php
   // غير هذا السطر
   require __DIR__.'/../vendor/autoload.php';
   // إلى
   require __DIR__.'/../../private_html/sahm/vendor/autoload.php';
   
   // وهذا السطر
   $app = require_once __DIR__.'/../bootstrap/app.php';
   // إلى
   $app = require_once __DIR__.'/../../private_html/sahm/bootstrap/app.php';
   ```

### 5️⃣ تشغيل أوامر Laravel

```bash
# في Terminal من cPanel أو SSH
cd /path/to/your/sahm/folder

# توليد مفتاح التشفير
php artisan key:generate

# تشغيل قاعدة البيانات
php artisan migrate --force

# إنشاء رابط symbolic للملفات
php artisan storage:link

# تحسين الأداء
php artisan config:cache
php artisan route:cache
php artisan view:cache

# تنظيف مخزن مؤقت
php artisan cache:clear
```

### 6️⃣ ضبط الأذونات

```bash
# أذونات المجلدات
chmod 755 storage/
chmod 755 storage/app/
chmod 755 storage/framework/
chmod 755 storage/logs/
chmod 755 bootstrap/cache/

# أذونات الملفات
chmod 644 .env
```

---

## إعداد المستخدم الافتراضي

### إنشاء مستخدم إداري:

```bash
# تشغيل في Terminal
php artisan tinker

# في Tinker اكتب:
$user = new App\Models\User();
$user->name = 'المدير العام';
$user->email = 'admin@yourdomain.com';
$user->password = Hash::make('strong_password_here');
$user->save();

exit
```

---

## فحص التثبيت

### ✅ تأكد من:

1. **الموقع يعمل**: افتح `https://yourdomain.com`
2. **قاعدة البيانات تعمل**: جرب تسجيل الدخول
3. **رفع الملفات يعمل**: جرب رفع صورة
4. **الأذونات صحيحة**: تأكد من كتابة الملفات

### 🔧 حل المشاكل الشائعة:

**خطأ 500**:
- تحقق من ملف `.env`
- تحقق من أذونات المجلدات
- راجع error logs في cPanel

**خطأ قاعدة البيانات**:
- تأكد من بيانات الاتصال في `.env`
- تأكد من تشغيل `php artisan migrate`

**خطأ الملفات**:
- تأكد من تشغيل `php artisan storage:link`
- تحقق من أذونات مجلد storage

---

## إعدادات الأمان الإضافية

### 1. حماية الملفات الحساسة:
```apache
# في .htaccess الرئيسي
<Files ".env">
    Order allow,deny
    Deny from all
</Files>
```

### 2. تفعيل HTTPS:
- من cPanel فعل SSL/TLS
- تأكد من `APP_URL=https://...`

### 3. نسخ احتياطية:
- اعمل نسخة احتياطية من قاعدة البيانات يومياً
- اعمل نسخة احتياطية من الملفات أسبوعياً

---

## الدعم والصيانة

### مراقبة الأداء:
- راقب مساحة التخزين
- راقب استهلاك قاعدة البيانات
- راقب error logs

### التحديثات:
- احدث Laravel بانتظام
- احدث PHP لآخر إصدار مدعوم
- راجع security updates

---

## معلومات الاتصال

في حالة وجود مشاكل:
1. راجع error logs في cPanel
2. تأكد من متطلبات الاستضافة
3. راجع هذا الدليل مرة أخرى

**نصائح مهمة**:
- احتفظ بنسخة احتياطية دائماً
- لا تشارك بيانات `.env` مع أحد
- استخدم كلمات مرور قوية
- فعل التحديثات الأمنية

---

*تم إعداد هذا الدليل خصيصاً لنظام SAHM لإدارة أوامر العمل* 