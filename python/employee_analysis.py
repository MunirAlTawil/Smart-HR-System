#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
سكربت تحليل الموظفين باستخدام الذكاء الصناعي
Employee Performance Analysis using Machine Learning

الهدف: تحليل أداء الموظف والتنبؤ باحتمالية ترك العمل والترقية باستخدام ML
"""

import sys
import json
import os
from datetime import datetime

# المكتبات المطلوبة للتعلم الآلي
try:
    import numpy as np
    import pandas as pd
    from sklearn.ensemble import RandomForestClassifier, RandomForestRegressor
    from sklearn.model_selection import train_test_split
    from sklearn.preprocessing import StandardScaler
    from sklearn.metrics import accuracy_score, r2_score
    ML_AVAILABLE = True
except ImportError:
    ML_AVAILABLE = False
    print(json.dumps({
        "error": "المكتبات المطلوبة غير متاحة",
        "turnover_probability": 50,
        "promotion_probability": 40,
        "commitment_index": 70,
        "skill_growth": 65,
        "confidence": 0,
        "recommendation": "يُرجى تثبيت pandas, numpy, scikit-learn"
    }, ensure_ascii=False))
    sys.exit(1)


class EmployeeAnalyzer:
    """محلل أداء الموظفين باستخدام الذكاء الصناعي"""
    
    def __init__(self):
        """تهيئة المحلل بإنشاء نماذج ML"""
        self.turnover_model = None
        self.promotion_model = None
        self.scaler = StandardScaler()
        self.models_trained = False
        
    def generate_training_data(self, num_samples=200):
        """
        إنشاء بيانات تدريب اصطناعية لتدريب النماذج
        
        Args:
            num_samples: عدد العينات المراد توليدها
        Returns:
            DataFrame يحتوي على البيانات
        """
        np.random.seed(42)  # للحصول على نتائج ثابتة
        
        data = {
            'employee_id': range(1, num_samples + 1),
            'performance_score': np.random.uniform(50, 100, num_samples),
            'attendance_rate': np.random.uniform(0.70, 1.0, num_samples),
            'skill_level': np.random.uniform(40, 100, num_samples),
            'projects_completed': np.random.randint(0, 20, num_samples),
            'training_hours': np.random.randint(0, 100, num_samples),
            'years_experience': np.random.uniform(0.5, 15, num_samples),
            'salary_level': np.random.choice([1, 2, 3], num_samples),  # 1=Low, 2=Medium, 3=High
            'last_promotion_years': np.random.uniform(0, 5, num_samples),
        }
        
        df = pd.DataFrame(data)
        
        # حساب احتمالية ترك العمل (Turnover)
        # العوامل المؤثرة: أداء منخفض، راتب منخفض، عدم ترقية، خبرة قليلة
        turnover_factors = (
            (100 - df['performance_score']) * 0.3 +
            (100 - df['attendance_rate'] * 100) * 0.2 +
            (4 - df['salary_level']) * 0.25 +
            df['last_promotion_years'] * 0.15 +
            np.random.normal(10, 15, num_samples)
        )
        df['turnover_probability'] = np.clip(turnover_factors, 0, 100)
        df['will_leave'] = (df['turnover_probability'] > 50).astype(int)
        
        # حساب احتمالية الترقية
        # العوامل المؤثرة: أداء عالي، مهارات متقدمة، مشاريع منجزة، تدريب
        promotion_factors = (
            df['performance_score'] * 0.3 +
            df['skill_level'] * 0.25 +
            df['projects_completed'] * 3 +
            df['training_hours'] * 0.3 +
            (df['years_experience'] > 3) * 10 +
            np.random.normal(15, 20, num_samples)
        )
        df['promotion_probability'] = np.clip(promotion_factors, 0, 100)
        df['ready_for_promotion'] = (df['promotion_probability'] > 60).astype(int)
        
        return df
    
    def train_models(self):
        """تدريب نماذج ML على البيانات الاصطناعية"""
        if not ML_AVAILABLE:
            return False
        
        # إنشاء بيانات التدريب
        training_data = self.generate_training_data(200)
        
        # تحديد الميزات (Features)
        features = [
            'performance_score', 'attendance_rate', 'skill_level',
            'projects_completed', 'training_hours', 'years_experience',
            'salary_level', 'last_promotion_years'
        ]
        
        X = training_data[features].values
        y_turnover = training_data['will_leave']
        y_promotion = training_data['ready_for_promotion']
        
        # تطبيق التوحيد القياسي
        X_scaled = self.scaler.fit_transform(X)
        
        # تقسيم البيانات
        X_train, X_test, y_turnover_train, y_turnover_test, y_promotion_train, y_promotion_test = \
            train_test_split(X_scaled, y_turnover, y_promotion, test_size=0.2, random_state=42)
        
        # تدريب نموذج ترك العمل (Classification)
        self.turnover_model = RandomForestClassifier(
            n_estimators=100,
            max_depth=10,
            random_state=42,
            min_samples_split=5
        )
        self.turnover_model.fit(X_train, y_turnover_train)
        
        # تدريب نموذج الترقية (Classification)
        self.promotion_model = RandomForestClassifier(
            n_estimators=100,
            max_depth=10,
            random_state=42,
            min_samples_split=5
        )
        self.promotion_model.fit(X_train, y_promotion_train)
        
        # حساب الدقة
        turnover_accuracy = accuracy_score(y_turnover_test, self.turnover_model.predict(X_test))
        promotion_accuracy = accuracy_score(y_promotion_test, self.promotion_model.predict(X_test))
        
        self.models_trained = True
        print(f"Models trained successfully!", file=sys.stderr)
        print(f"Turnover Model Accuracy: {turnover_accuracy:.2%}", file=sys.stderr)
        print(f"Promotion Model Accuracy: {promotion_accuracy:.2%}", file=sys.stderr)
        
        return True
    
    def analyze_employee(self, employee_data):
        """
        تحليل بيانات موظف معين
        
        Args:
            employee_data: قاموس يحتوي على بيانات الموظف
        Returns:
            قاموس يحتوي على نتائج التحليل
        """
        if not self.models_trained:
            self.train_models()
        
        # استخراج الميزات من بيانات الموظف
        features = [
            employee_data.get('performance_score', 75),
            employee_data.get('attendance_rate', 0.9),
            employee_data.get('skill_level', 70),
            employee_data.get('projects_completed', 5),
            employee_data.get('training_hours', 20),
            employee_data.get('years_experience', 2),
            employee_data.get('salary_level', 2),  # Default: Medium
            employee_data.get('last_promotion_years', 2)
        ]
        
        # تحويل إلى numpy array وتوحيد القياس
        features_array = np.array(features).reshape(1, -1)
        features_scaled = self.scaler.transform(features_array)
        
        # التنبؤ
        turnover_proba = self.turnover_model.predict_proba(features_scaled)[0]
        promotion_proba = self.promotion_model.predict_proba(features_scaled)[0]
        
        # حساب النتائج
        turnover_score = int(turnover_proba[1] * 100)  # احتمالية ترك العمل
        promotion_score = int(promotion_proba[1] * 100)  # احتمالية الترقية
        
        # حساب المؤشرات الإضافية
        commitment_index = int(min(100, (
            features[1] * 100 * 0.4 +  # attendance_rate
            features[0] * 0.3 +  # performance_score
            features[2] * 0.3  # skill_level
        )))
        
        skill_growth = int(min(100, (
            features[4] * 0.5 +  # training_hours
            features[3] * 5 +  # projects_completed
            features[2] * 0.3  # skill_level
        )))
        
        # نسبة الثقة بناءً على كمية البيانات
        confidence = min(100, 70 + turnover_score // 3 + promotion_score // 3)
        
        # التوصية الذكية
        recommendation = self.generate_recommendation(
            turnover_score, promotion_score, features
        )
        
        return {
            "turnover_probability": turnover_score,
            "promotion_probability": promotion_score,
            "commitment_index": commitment_index,
            "skill_growth": skill_growth,
            "confidence": confidence,
            "recommendation": recommendation,
            "status": "success",
            "analysis_date": datetime.now().strftime("%Y-%m-%d %H:%M:%S")
        }
    
    def generate_recommendation(self, turnover_score, promotion_score, features):
        """
        توليد توصية ذكية بناءً على النتائج
        
        Args:
            turnover_score: احتمالية ترك العمل
            promotion_score: احتمالية الترقية
            features: ميزات الموظف
        Returns:
            نص التوصية
        """
        performance = features[0]
        attendance = features[1]
        skill_level = features[2]
        
        # سيناريوهات التوصيات
        if turnover_score > 70:
            return "🔴 خطر مرتفع! يُنصح بإجراء مقابلة فورية لفهم الاحتياجات والاحتفاظ بالموظف"
        
        if turnover_score > 50 and turnover_score <= 70:
            if promotion_score > 60:
                return "🟡 مخاطر متوسطة مع استعداد للترقية. يُنصح بترقية الموظف لزيادة ولائه"
            else:
                return "🟡 يُنصح بمتابعة دورية وتوفير تحفيز إضافي للموظف"
        
        if promotion_score > 75:
            if turnover_score < 30:
                return "✅ يوصى بالترقية خلال 3-6 أشهر القادمة. الموظف متميز ومستقر"
            else:
                return "🟢 الموظف جاهز للترقية. يُنصح بتقييم الفرص المتاحة الآن"
        
        if promotion_score > 60:
            return "📅 يمكن النظر للترقية خلال 6-12 شهراً مع التركيز على التطوير المهني"
        
        if promotion_score < 40:
            if attendance > 0.85 and performance > 70:
                return "🔵 يُنصح بالاحتفاظ بالموظف في موقعه والتركيز على تطوير مهاراته"
            else:
                return "⚪ يحتاج للمزيد من التطوير قبل النظر للترقية"
        
        return "📊 حالة طبيعية. يُنصح بمراقبة التطور المستمر"
    
    def analyze_from_json(self, json_data):
        """
        تحليل من بيانات JSON
        
        Args:
            json_data: سلسلة JSON أو قاموس
        Returns:
            نتائج التحليل
        """
        if isinstance(json_data, str):
            employee_data = json.loads(json_data)
        else:
            employee_data = json_data
        
        return self.analyze_employee(employee_data)


def main():
    """الدالة الرئيسية"""
    try:
        # قراءة بيانات الموظف من الوسائط أو stdin
        if len(sys.argv) > 1:
            input_data = sys.argv[1]
        else:
            # قراءة من stdin إذا لم يتم تمرير من سطر الأوامر
            input_data = sys.stdin.read()
        
        employee_data = json.loads(input_data)
        
        # إنشاء المحلل وتشغيل التحليل
        analyzer = EmployeeAnalyzer()
        results = analyzer.analyze_from_json(employee_data)
        
        # إرجاع النتائج كـ JSON
        print(json.dumps(results, ensure_ascii=False, indent=2))
        
    except json.JSONDecodeError as e:
        error_result = {
            "error": f"خطأ في قراءة JSON: {str(e)}",
            "turnover_probability": 50,
            "promotion_probability": 40,
            "commitment_index": 70,
            "skill_growth": 65,
            "confidence": 0,
            "recommendation": "تحقق من صحة البيانات المدخلة",
            "status": "error"
        }
        print(json.dumps(error_result, ensure_ascii=False))
        sys.exit(1)
    
    except Exception as e:
        error_result = {
            "error": f"خطأ غير متوقع: {str(e)}",
            "turnover_probability": 50,
            "promotion_probability": 40,
            "commitment_index": 70,
            "skill_growth": 65,
            "confidence": 0,
            "recommendation": "حدث خطأ أثناء التحليل",
            "status": "error"
        }
        print(json.dumps(error_result, ensure_ascii=False))
        sys.exit(1)


if __name__ == '__main__':
    main()

