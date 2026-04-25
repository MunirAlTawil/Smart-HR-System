# سكربت تحليل الموظفين باستخدام الذكاء الصناعي

## 📋 نظرة عامة
`employee_analysis.py` - سكربت Python متقدم لتحليل أداء الموظفين باستخدام خوارزميات Machine Learning.

## 🎯 الهدف
تحليل بيانات الموظفين والتنبؤ بـ:
- احتمال ترك العمل (Turnover Probability)
- احتمال الترقية (Promotion Probability)
- مؤشر الالتزام (Commitment Index)
- مستوى التطور المهاري (Skill Growth)
- نسبة الثقة في التحليل (Confidence)

## 🔧 المكتبات المطلوبة

```bash
pip install -r requirements.txt
```

**المكتبات الأساسية:**
- `pandas` - معالجة البيانات
- `numpy` - العمليات الرياضية
- `scikit-learn` - تعلم الآلة
- `joblib` - حفظ/تحميل النماذج (اختياري)

## 📊 بنية السكربت

### 1. Class: EmployeeAnalyzer
**الوظائف:**

#### `generate_training_data(num_samples=200)`
- ينشئ بيانات تدريب اصطناعية
- يحسب احتمالية ترك العمل بناءً على عوامل متعددة
- يحسب احتمالية الترقية بناءً على الأداء والمهارات
- يرجع DataFrame جاهز للتدريب

#### `train_models()`
- يدرّب نموذجين من RandomForest:
  - نموذج ترك العمل (Classification)
  - نموذج الترقية (Classification)
- يستخدم المعايرة القياسية (StandardScaler)
- يحسب دقة النماذج

#### `analyze_employee(employee_data)`
- يحلل بيانات موظف معين
- يرجع نتائج شاملة مع توصيات

#### `generate_recommendation()`
- يولد توصية ذكية بناءً على النتائج
- يستخدم عدة سيناريوهات منطقية

## 📥 الإدخال (Input)

```json
{
  "employee_id": 12,
  "performance_score": 78,
  "attendance_rate": 0.9,
  "skill_level": 65,
  "projects_completed": 5,
  "training_hours": 20,
  "years_experience": 3,
  "salary_level": 2,
  "last_promotion_years": 2
}
```

## 📤 الإخراج (Output)

```json
{
  "turnover_probability": 45,
  "promotion_probability": 60,
  "commitment_index": 80,
  "skill_growth": 75,
  "confidence": 85,
  "recommendation": "يُنصح بترقية الموظف خلال 3 أشهر",
  "status": "success",
  "analysis_date": "2025-01-27 14:30:00"
}
```

## 🚀 الاستخدام

### من سطر الأوامر:
```bash
python python/employee_analysis.py '{"employee_id":1,"performance_score":85,"attendance_rate":0.95,"skill_level":80}'
```

### من Laravel:
```php
$pythonScript = base_path('python/employee_analysis.py');
$jsonData = json_encode($employeeData, JSON_UNESCAPED_UNICODE);
$command = "python \"" . $pythonScript . "\" " . escapeshellarg($jsonData);
$output = shell_exec($command);
$result = json_decode($output, true);
```

## 🧠 النماذج المستخدمة

### RandomForest Classifier
- **عدد الأشجار**: 100
- **العمق الأقصى**: 10
- **دقة التدريب**: ~85-92%

**الميزات المستخدمة:**
- أداء الموظف (performance_score)
- معدل الحضور (attendance_rate)
- مستوى المهارات (skill_level)
- عدد المشاريع المكتملة (projects_completed)
- ساعات التدريب (training_hours)
- سنوات الخبرة (years_experience)
- مستوى الراتب (salary_level)
- سنوات منذ آخر ترقية (last_promotion_years)

## 💡 التوصيات الذكية

السكربت يولد توصيات بناءً على عدة سيناريوهات:

1. **خطر عالي (Turnover > 70%)**
   - يوصى بمقابلة فورية

2. **ترقية جاهزة (Promotion > 75%)**
   - يوصى بالترقية خلال 3-6 أشهر

3. **مخاطر متوسطة (Turnover 50-70%)**
   - يوصى بمتابعة وتحفيز

4. **جاهز للترقية (Promotion > 60%)**
   - يمكن النظر للترقية خلال 6-12 شهر

5. **تحتاج تطوير**
   - التركيز على التدريب والتطوير

## 🔄 الربط مع Laravel

السكربت متصل تلقائياً مع Laravel عبر `AdminController@runAnalysis`:
- Laravel يمرر بيانات الموظف
- Python يحلل البيانات
- النتيجة تُعاد ويعرضها Laravel في الواجهة

## 📝 ملاحظات مهمة

1. **البيانات الحالية تجريبية** - في الإنتاج، يجب جلب بيانات حقيقية من قاعدة البيانات
2. **النماذج تُدرّب فوراً** - في الإنتاج، يُفضل حفظ النماذج المدربة
3. **البيانات المحسوبة** - commitment_index و skill_growth محسوبة، ليست من ML

## 🛠️ التحسينات المستقبلية

- حفظ النماذج المدربة باستخدام joblib
- إضافة المزيد من الميزات (نصوص، صور، إلخ)
- دمج بيانات تاريخية حقيقية
- تحسين دقة النماذج
- إضافة تفسير للنتائج (Explainable AI)

---

**تم بنجاح! ✅**

