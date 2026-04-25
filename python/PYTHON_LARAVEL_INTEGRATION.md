# دليل دمج Python مع Laravel

## 📋 نظرة عامة

تم ربط سكربت Python `analyze_cv.py` مع Laravel بنجاح. هذا الدليل يوضح كيفية عمل التكامل.

## 🔄 آلية العمل

### 1. في Laravel (ApplicationController.php)

عند تقديم طلب وظيفي:

```php
// حفظ ملف السيرة الذاتية
$cvPath = $request->file('cv')->store('cvs', 'public');

// استدعاء سكربت Python
$analysis = $this->analyzeCV($cvPath, $jobSkills, $jobTitle);
```

### 2. استدعاء Python

```php
$command = "python " . escapeshellarg($pythonScript) . " " 
            . escapeshellarg($fullCvPath) . " " 
            . escapeshellarg($jobTitle);

$output = shell_exec($command);
$result = json_decode($output, true);
```

### 3. سكربت Python يعيد JSON

```json
{
  "match": 80.5,
  "matched_skills": ["python", "laravel", "mysql"],
  "missing_skills": ["redis"],
  "total_required_skills": 4,
  "matched_count": 3
}
```

### 4. Laravel يحفظ النتيجة

```php
Application::create([
    'match_percentage' => $result['match'],
    'matched_skills' => implode(', ', $result['matched_skills']),
    'missing_skills' => implode(', ', $result['missing_skills']),
]);
```

## ⚙️ الخطوات المطلوبة

### 1. تثبيت المكتبات المطلوبة

```bash
cd python
pip install -r requirements.txt
```

### 2. التأكد من مسار Python

في Windows:
```bash
python --version
```

في Linux/Mac:
```bash
python3 --version
```

### 3. تعديل Laravel (إذا لزم)

في `ApplicationController.php`، السطر 97:

**Windows:**
```php
$command = "python " . escapeshellarg($pythonScript) ...
```

**Linux/Mac:**
```php
$command = "python3 " . escapeshellarg($pythonScript) ...
```

## 🧪 اختبار التكامل

### اختبار يدوي من Laravel

```php
// في tinker
php artisan tinker

$pythonScript = base_path('python/analyze_cv.py');
$cvPath = storage_path('app/public/cvs/test_cv.pdf');
$jobTitle = "Web Developer";

$command = "python " . escapeshellarg($pythonScript) . " " 
            . escapeshellarg($cvPath) . " " 
            . escapeshellarg($jobTitle);

$output = shell_exec($command);
dd(json_decode($output, true));
```

### اختبار مباشر من Terminal

```bash
python python/analyze_cv.py "storage/app/public/cvs/test_cv.pdf" "Web Developer"
```

## 🐛 حل المشاكل

### خطأ: Python غير موجود

**الخطأ:**
```
'shell_exec' has been disabled for security reasons
```

**الحل:**
في `php.ini`:
```ini
disable_functions = ; # إزالة shell_exec من القائمة
```

### خطأ: مسار الملف غير صحيح

**التحقق:**
```php
// في ApplicationController
dd([
    'cv_path' => storage_path('app/public/' . $cvPath),
    'exists' => file_exists(storage_path('app/public/' . $cvPath)),
    'python_script' => base_path('python/analyze_cv.py'),
    'python_exists' => file_exists(base_path('python/analyze_cv.py')),
]);
```

### خطأ: المكتبات غير مثبتة

**الخطأ:**
```
ModuleNotFoundError: No module named 'pdfplumber'
```

**الحل:**
```bash
cd python
pip install pdfplumber python-docx
```

### خطأ: الوظيفة غير موجودة

**الخطأ في Python:**
```json
{
  "error": "الوظيفة 'Web Developer' غير موجودة"
}
```

**الحل:**
أضف الوظيفة إلى `python/job_skills.json`:

```json
{
  "Web Developer": [
    "html", "css", "javascript", "php", "laravel"
  ]
}
```

## 📊 مثال على الاستخدام الكامل

### 1. إنشاء وظيفة (من لوحة التحكم)

```
الوظيفة: Web Developer
المهارات المطلوبة: HTML, CSS, JavaScript, PHP, Laravel
```

### 2. تقديم طلب وظيفي

المستخدم يرفع سيرته الذاتية عبر `/jobs/{id}`

### 3. تحليل تلقائي

- Python يستخرج النص من السيرة الذاتية
- يقارن المهارات مع `job_skills.json`
- يرجع نسبة التطابق

### 4. عرض النتيجة

المستخدم يرى في `/result/{id}`:
- نسبة التطابق: 75%
- المهارات المتطابقة: HTML, CSS, PHP
- المهارات الناقصة: JavaScript, Laravel

## 🔧 تحسينات مستقبلية

### استخدام Queue

بدلاً من `shell_exec` المباشر:

```php
// في Job
AnalyzeCVJob::dispatch($cvPath, $jobTitle);
```

### تحسين الأمان

```php
// استخدام Process من Symfony
use Symfony\Component\Process\Process;

$process = new Process(['python', $pythonScript, $cvPath, $jobTitle]);
$process->setTimeout(30);
$process->run();
```

### دعم المزيد من الملفات

إضافة دعم لـ:
- `.txt`
- `.rtf`
- الصور باستخدام OCR

## 📝 ملاحظات مهمة

1. **الأمان**: تأكد من التحقق من مسار الملفات
2. **Timeouts**: حدد مهلة زمنية قصوى للتحليل
3. **Logging**: سجل جميع الأخطاء
4. **Backup**: استخدم Simple Analysis كحل بديل

## ✅ التحقق النهائي

تأكد من:
- ✅ تثبيت Python
- ✅ تثبيت المكتبات المطلوبة
- ✅ ملف job_skills.json موجود
- ✅ صلاحيات المجلدات صحيحة
- ✅ shell_exec مفعّل في PHP

## 📞 الدعم

إذا واجهت مشاكل:
1. راجع ملف `storage/logs/laravel.log`
2. اختبر السكربت يدوياً من Terminal
3. تحقق من مسارات الملفات

