# تحليل السير الذاتية - CV Analysis Script

## 📋 الوصف

سكربت Python لتحليل السير الذاتية وتحديد مدى تطابق مهارات المرشح مع متطلبات الوظيفة باستخدام معالجة النصوص والذكاء الاصطناعي البسيط.

## ✨ الميزات الجديدة

- ✅ **Case Insensitive Matching**: يتعامل مع اختلاف حالة الأحرف (React = react)
- ✅ **Symbol Aware**: يتعامل مع الرموز الخاصة (C#)
- ✅ **Multiple Search Methods**: يستخدم 3 طرق مختلفة للبحث لضمان الدقة
- ✅ **Space Independent**: يتعامل مع المسافات بطريقة ذكية

## ⚙️ المتطلبات

### 1. تثبيت المكتبات

```bash
cd python
pip install -r requirements.txt
```

### 2. مكتبات الأساسية المطلوبة

- **pdfplumber**: لقراءة ملفات PDF
- **python-docx**: لقراءة ملفات DOCX

## 🚀 الاستخدام

### الاستخدام الأساسي

```bash
python analyze_cv.py <path_to_cv> <job_title>
```

### أمثلة

```bash
# مثال 1: تحليل سيرة ذاتية لمطور ويب
python analyze_cv.py "uploads/cv.pdf" "Web Developer"

# مثال 2: تحليل سيرة ذاتية لعالم بيانات
python analyze_cv.py "documents/resume.docx" "Data Scientist"

# مثال 3: تحليل سيرة ذاتية لمدير مشروع
python analyze_cv.py "cv_folder/candidate.pdf" "Project Manager"
```

## 📤 المخرجات

السكربت يطبع نتيجة بصيغة JSON:

```json
{
  "match": 80.5,
  "matched_skills": ["python", "machine learning", "pandas"],
  "missing_skills": ["numpy", "statistics"],
  "total_required_skills": 5,
  "matched_count": 3
}
```

### شرح الحقول

- **match**: نسبة التطابق (من 0 إلى 100)
- **matched_skills**: المهارات التي وجدت في السيرة الذاتية
- **missing_skills**: المهارات المطلوبة غير الموجودة
- **total_required_skills**: إجمالي المهارات المطلوبة
- **matched_count**: عدد المهارات المطابقة

## 📁 الملفات

- **analyze_cv.py**: السكربت الرئيسي
- **job_skills.json**: قاعدة بيانات المهارات المطلوبة لكل وظيفة
- **requirements.txt**: قائمة المكتبات المطلوبة

## 🛠️ إضافة وظائف جديدة

لتعديل أو إضافة مهارات لوظيفة معينة، قم بتعديل ملف `job_skills.json`:

```json
{
  "Job Title": [
    "skill1",
    "skill2",
    "skill3"
  ]
}
```

## 🔧 الاستخدام من Laravel

في Laravel، يمكنك استدعاء السكربت باستخدام `shell_exec()`:

```php
$cvPath = storage_path('app/public/cvs/example.pdf');
$jobTitle = "Web Developer";
$pythonScript = base_path('python/analyze_cv.py');

$command = "python " . escapeshellarg($pythonScript) . " " 
            . escapeshellarg($cvPath) . " " 
            . escapeshellarg($jobTitle);

$output = shell_exec($command);
$result = json_decode($output, true);
```

## ⚠️ ملاحظات مهمة

1. تأكد من تثبيت Python 3.6 أو أحدث
2. الملفات المدعومة: PDF, DOCX
3. تأكد من وجود جميع المكتبات المطلوبة
4. يجب أن يكون الوصول لمسار الملف مُصرحاً به

## 🐛 حل المشاكل

### خطأ: مكتبة غير موجودة
```bash
pip install pdfplumber python-docx
```

### خطأ: ملف غير موجود
تأكد من صحة مسار ملف السيرة الذاتية

### خطأ: وظيفة غير موجودة
أضف الوظيفة إلى ملف `job_skills.json`

## 📊 كيف يعمل السكربت

1. **قراءة المدخلات**: يأخذ مسار ملف السيرة الذاتية واسم الوظيفة
2. **استخراج النص**: يستخرج النص من PDF أو DOCX
3. **تنظيف النص**: يزيل الرموز والأرقام ويعيد تحويل النص
4. **تحميل المهارات**: يحمّل المهارات المطلوبة من ملف JSON
5. **المقارنة**: يقارن المهارات الموجودة بالمطلوبة
6. **حساب النسبة**: يحسب نسبة التطابق
7. **المخرجات**: يعيد نتيجة JSON

## 📝 التطوير المستقبلي

- [ ] دعم اللغة العربية بشكل أفضل
- [ ] إضافة تحليل أكثر تقدماً باستخدام NLTK
- [ ] دعم ملفات Word (.doc)
- [ ] إضافة تقييم مستوى الخبرة
- [ ] دعم الصور والـ OCR

