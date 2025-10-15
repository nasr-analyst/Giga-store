# 📋 خطة المشروع المبسطة

## 👥 مهام الفريق

### فريق الواجهات (Frontend)
*المطلوب:*
- صفحة تسجيل الدخول (login.html)
- صفحة إنشاء حساب (register.html) 
- صفحة المنتجات (products.html)
- صفحة تفاصيل منتج (product-details.html)
- تنسيقات CSS لجميع الصفحات

### فريق الـ (Backend)  
*المطلوب:*
- تصميم قاعدة البيانات
- موديل المنتجات (ProductModel.php)
- إعدادات الاتصال بقاعدة البيانات

## 📁 هيكل المشروع الكامل


ecommerce-project/
├── 📄 index.html
├── 📄 login.html
├── 📄 register.html
├── 📄 products.html
├── 📄 product-details.html
├── 📁 css/
│   ├── style.css
│   ├── auth.css
│   └── products.css
├── 📁 app/
│   ├── 📁 models/
│   │   └── ProductModel.php
│   └── 📁 config/
│       └── database.php
├── 📁 database/
│   └── schema.sql
└── 📁 assets/
    ├── images/
    └── js/


## 🚀 طريقة العمل على GitHub

### الخطوات:
1. *كل عضو ينسخ المشروع:*
   bash
   git clone [رابط المشروع]
   

2. *كل عضو يروح لفرعه:*
   bash
   git checkout frontend-member1  # أو backend-member1
   

3. *العمل على المهام وحفظ التغييرات:*
   bash
   git add .
   git commit -m "تم إنشاء صفحة التسجيل"
   git push origin frontend-member1
   

4. *عمل Pull Request عندما تنتهي المهمة*

### توزيع الـ Branches:
- main - الكود النهائي
- frontend-member1 - لصفحات التسجيل
- frontend-member2 - لصفحات المنتجات  
- backend-member1 - لقاعدة البيانات
- backend-member2 - لموديل المنتجات

كل عضو يشتغل على فرعه ويرفع التغييرات يومياً! 🎯
