# 🚀 Quick Reference Guide - مرجع سريع

## الاستخدام الأساسي

```php
// ✅ استخدم العلاقات
User::find(1)->visits;

// ❌ تجنب الاستعلامات اليدوية
Visit::where('user_id', 1)->get();
```

---

## جدول مرجعي سريع

| النموذج     | العلاقة  | الكود                                        |
| ----------- | -------- | -------------------------------------------- |
| **User**    | الزيارات | `$user->visits`                              |
| **User**    | الأحداث  | `$user->workEvents`                          |
| **User**    | المدارس  | `$user->allAssociatedSchools`                |
| **School**  | الزيارات | `$school->visits`                            |
| **School**  | الحقول   | `$school->coordinator`, `$school->principal` |
| **Visit**   | المستخدم | `$visit->user`                               |
| **Visit**   | المدرسة  | `$visit->school`                             |
| **Visit**   | المرفقات | `$visit->attachments`                        |
| **Program** | الدورات  | `$program->programCycles`                    |
| **Program** | الزيارات | `$program->visits`                           |
| **Sector**  | المدارس  | `$sector->schools`                           |
| **Sector**  | الزيارات | `$sector->visits`                            |

---

## أنماط الاستعلام الشائعة

### 1. تحميل البيانات المرتبطة

```php
// تحميل مستخدم مع الزيارات
User::with('visits')->find(1);

// تحميل متعدد المستويات
Visit::with(['user', 'school', 'attachments'])->get();
```

### 2. البحث المتقدم

```php
// البحث عن السجلات التي بها علاقات
School::whereHas('visits')->get();

// البحث عن السجلات بدون علاقات
School::whereDoesntHave('visits')->get();

// البحث مع شروط على العلاقات
User::whereHas('visits', function($q) {
    $q->where('visit_type', 'فنية');
})->get();
```

### 3. العد والإحصائيات

```php
// عد العلاقات
User::withCount('visits')->get();
// استخدام: $user->visits_count

// عد متعدد
User::withCount('visits', 'workEvents')->get();

// عد مع شروط
User::withCount(['visits' => function($q) {
    $q->whereYear('visit_date', 2026);
}])->get();
```

### 4. الفرز والترتيب

```php
// الفرز حسب العلاقات
Visit::with('user')
    ->orderBy('visit_date', 'desc')
    ->get();

// الفرز حسب عد العلاقات
User::withCount('visits')
    ->orderByDesc('visits_count')
    ->get();
```

### 5. التصفية والحد

```php
// اختيار الحقول المطلوبة
Visit::with('user:id,name', 'school:id,name')->get();

// الحد من النتائج
User::find(1)->visits()->limit(10)->get();

// التقسيم
Visit::all()->chunk(100);
```

---

## أنماط الأداء

### ✅ الأسرع

```php
// استعلام واحد
User::withCount('visits')->get(); // 1 query

// eager loading
User::with('visits')->get(); // 2 queries
```

### ❌ الأبطأ

```php
// لكل سجل استعلام جديد
$users = User::all(); // 1 query
foreach ($users as $user) {
    echo $user->visits()->count(); // 100 queries
}

// المجموع: 101 query!
```

---

## أمثلة عملية سريعة

### تقرير المستخدم

```php
$user = User::with([
    'visits.school',
    'workEvents',
    'sector'
])->find($id);
```

### تقرير المدرسة

```php
$school = School::withCount('visits')
    ->with(['coordinator', 'principal'])
    ->find($id);
```

### تقرير البرنامج

```php
$program = Program::with([
    'programCycles.schools',
    'programCycles.indicators'
])->find($id);
```

### جميع الزيارات الحديثة

```php
$visits = Visit::with(['user', 'school'])
    ->latest('visit_date')
    ->limit(20)
    ->get();
```

---

## الخطأ vs الصحيح

### الخطأ الأول: N+1 Problem

```php
// ❌ 101 query
foreach (User::all() as $user) {
    $count = $user->visits()->count();
}

// ✅ 1 query
User::withCount('visits')->get();
```

### الخطأ الثاني: تحميل جميع البيانات

```php
// ❌ تحميل كل الحقول
Visit::all();

// ✅ تحميل الحقول المطلوبة
Visit::select('id', 'user_id', 'school_id', 'visit_date')->get();
```

### الخطأ الثالث: عدم استخدام whereHas

```php
// ❌ تحميل الكل ثم التصفية
$schools = School::all()
    ->filter(fn($s) => $s->visits()->count() > 0);

// ✅ الفلترة في الـ query
$schools = School::whereHas('visits')->get();
```

---

## الملفات المرجعية

| الملف                                    | الهدف                       |
| ---------------------------------------- | --------------------------- |
| `RELATIONSHIPS.md`                       | شرح تفصيلي لكل العلاقات     |
| `RELATIONSHIPS_EXAMPLES.php`             | 40+ مثال عملي               |
| `CONTROLLERS_AND_RESOURCES_EXAMPLES.php` | أمثلة في Controllers و APIs |
| `BEFORE_AND_AFTER.md`                    | مقارنة شاملة                |
| `RelationshipsTest.php`                  | اختبارات                    |

---

## الأوامر المفيدة

```bash
# اختبار العلاقات
php artisan test tests/Unit/RelationshipsTest.php

# استخدام Tinker
php artisan tinker

# عرض استعلامات SQL
# في .env: APP_DEBUG=true
# ثم استخدم: Debugbar أو SQL Log
```

---

## تلميحات Tinker

```bash
php artisan tinker

# عرض مستخدم مع الزيارات
>>> User::with('visits')->find(1)

# عد الزيارات
>>> User::find(1)->visits()->count()

# الزيارات الأخيرة
>>> Visit::latest('visit_date')->first()

# المدارس النشطة
>>> School::whereHas('visits')->count()

# إحصائيات
>>> User::withCount('visits', 'workEvents')->first()
```

---

## قائمة تحقق للأداء الأمثل

- [ ] استخدم `with()` بدل lazy loading
- [ ] استخدم `withCount()` للعد
- [ ] استخدم `select()` للحقول المطلوبة
- [ ] استخدم `whereHas()` للبحث
- [ ] تجنب الحلقات مع الاستعلامات
- [ ] استخدم `chunk()` للبيانات الكبيرة
- [ ] استخدم `cache()` للبيانات الثابتة
- [ ] اختبر مع `php artisan debugbar`

---

**آخر تحديث:** 17 يناير 2026  
**النسخة:** 1.0.0  
**الحالة:** ✅ جاهز للاستخدام
