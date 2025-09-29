# newSystem
نظام لإدراة الأخبار باستخدام PHP وMySQL

نظام إدارة الأخبار

نظام متكامل لإدارة الأخبار باستخدام PHP و MySQL مع واجهة مستخدم عربية متجاوبة.

المميزات

- نظام مستخدمين كامل (تسجيل دخول وتسجيل خروج)
- إدارة الفئات والأخبار
- حذف منطقي مع إمكانية الاستعادة
- واجهة مستخدم عربية متجاوبة
- رفع الصور مع التحقق
- حماية من الهجمات الأمنية

المتطلبات

- PHP إصدار 7.4 أو أحدث
- MySQL إصدار 5.7 أو أحدث
- خادم Apache أو Nginx

خطوات التثبيت

1. نسخ المستودع:
   
   git clone https://github.com/leenqueshta-design/newSystem.git
   

2. استيراد قاعدة البيانات:
   - أنشئ قاعدة بيانات جديدة
   - استخدم ملف SQL الموجود في مجلد database

3. تعديل الإعدادات:
   - عدل ملف config.php لإعدادات قاعدة البيانات

4. ضبط الصلاحيات:
   
   chmod 755 uploads/
   

هيكل المشروع


newsystem/
├── config.php
├── login.php
├── register.php
├── dashboard.php
├── addCategory.php
├── viewCategories.php
├── addNews.php
├── viewNews.php
├── editNews.php
├── deleteNews.php
├── viewDeletedNews.php
├── logout.php
├── style.css
└── uploads/


بيانات الدخول الافتراضية

- البريد الإلكتروني: admin@news.com
- كلمة المرور: 123456

الأمان

- حماية من هجمات SQL Injection
- حماية من هجمات XSS
- تشفير كلمات المرور
- جلسات آمنة

الرخصة

هذا المشروع مرخص تحت رخصة MIT.
