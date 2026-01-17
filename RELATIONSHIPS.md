# علاقات نماذج البيانات (Database Relationships)

## ملخص شامل للعلاقات بين جداول قاعدة البيانات

### 1. **User Model** ✅

```
User (المستخدم)
├─ sector: BelongsTo → Sector (القطاع التعليمي)
├─ schoolsAsCoordinator: HasMany → School (المدارس كمنسق)
├─ schoolsAsPrincipal: HasMany → School (المدارس كمدير)
├─ allAssociatedSchools: Union → School (جميع المدارس)
├─ visits: HasMany → Visit (الزيارات)
├─ workEvents: HasMany → WorkEvent (الأحداث)
└─ uploadedAttachments: HasMany → VisitAttachment (المرفقات المرفوعة)
```

---

### 2. **School Model** ✅

```
School (المدرسة)
├─ sector: BelongsTo → Sector (القطاع)
├─ coordinator: BelongsTo → User (المنسق)
├─ principal: BelongsTo → User (المدير)
├─ programCycles: BelongsToMany → ProgramCycle (البرامج) - via program_cycle_school
├─ visits: HasMany → Visit (الزيارات)
└─ workEvents: HasManyThrough → WorkEvent (الأحداث عبر الزيارات)
```

---

### 3. **Sector Model** ✅

```
Sector (القطاع التعليمي)
├─ schools: HasMany → School (المدارس)
├─ users: HasMany → User (المستخدمون)
├─ visits: HasManyThrough → Visit (الزيارات عبر المدارس)
└─ programCycles: HasManyThrough → ProgramCycle (دورات البرامج عبر المدارس)
```

---

### 4. **Visit Model** ✅

```
Visit (الزيارة)
├─ user: BelongsTo → User (المستخدم)
├─ school: BelongsTo → School (المدرسة)
├─ academicYear: BelongsTo → AcademicYear (السنة الدراسية)
├─ programCycle: BelongsTo → ProgramCycle (دورة البرنامج)
└─ attachments: HasMany → VisitAttachment (المرفقات)
```

---

### 5. **VisitAttachment Model** ✅

```
VisitAttachment (مرفق الزيارة)
├─ visit: BelongsTo → Visit (الزيارة)
└─ uploadedBy: BelongsTo → User (من قام برفعها)
```

---

### 6. **WorkEvent Model** ✅

```
WorkEvent (حدث العمل)
├─ user: BelongsTo → User (المستخدم)
├─ academicYear: BelongsTo → AcademicYear (السنة الدراسية)
└─ programCycle: BelongsTo → ProgramCycle (دورة البرنامج)
```

---

### 7. **Program Model** ✅

```
Program (البرنامج)
├─ programCycles: HasMany → ProgramCycle (دورات البرنامج)
├─ indicators: HasManyThrough → ProgramCycleIndicator (المؤشرات عبر الدورات)
├─ visits: HasManyThrough → Visit (الزيارات عبر الدورات)
└─ workEvents: HasManyThrough → WorkEvent (الأحداث عبر الدورات)
```

---

### 8. **ProgramCycle Model** ✅

```
ProgramCycle (دورة البرنامج)
├─ program: BelongsTo → Program (البرنامج)
├─ academicYear: BelongsTo → AcademicYear (السنة الدراسية)
├─ indicators: HasMany → ProgramCycleIndicator (المؤشرات)
├─ schools: BelongsToMany → School (المدارس) - via program_cycle_school
├─ visits: HasMany → Visit (الزيارات)
└─ workEvents: HasMany → WorkEvent (الأحداث)
```

---

### 9. **ProgramCycleIndicator Model** ✅

```
ProgramCycleIndicator (مؤشر دورة البرنامج)
├─ programCycle: BelongsTo → ProgramCycle (دورة البرنامج)
└─ program: BelongsToThrough → Program (البرنامج)
```

---

### 10. **AcademicYear Model** ✅

```
AcademicYear (السنة الدراسية)
├─ programCycles: HasMany → ProgramCycle (دورات البرامج)
├─ programCycleIndicators: HasManyThrough → ProgramCycleIndicator (المؤشرات)
├─ visits: HasMany → Visit (الزيارات)
└─ workEvents: HasMany → WorkEvent (الأحداث)
```

---

## أمثلة استخدام العلاقات

### مثال 1: الحصول على جميع الزيارات لمستخدم معين

```php
$user = User::find(1);
$visits = $user->visits; // جميع الزيارات
$workEvents = $user->workEvents; // جميع الأحداث
```

### مثال 2: الحصول على جميع الزيارات في مدرسة معينة مع التفاصيل

```php
$school = School::find(1);
$visits = $school->visits()
    ->with('user', 'academicYear', 'programCycle')
    ->get();
```

### مثال 3: الحصول على جميع المؤشرات لبرنامج معين

```php
$program = Program::find(1);
$indicators = $program->indicators;
```

### مثال 4: الحصول على جميع الزيارات في قطاع معين

```php
$sector = Sector::find(1);
$visits = $sector->visits; // عبر العلاقة HasManyThrough
```

### مثال 5: الحصول على جميع المدارس للمستخدم (منسق ومدير)

```php
$user = User::find(1);
$coordinatedSchools = $user->schoolsAsCoordinator;
$principalSchools = $user->schoolsAsPrincipal;
$allSchools = $user->allAssociatedSchools; // جميع المدارس
```

### مثال 6: الحصول على مرفقات الزيارة مع معلومات من رفعها

```php
$visit = Visit::find(1);
$attachments = $visit->attachments()
    ->with('uploadedBy')
    ->get();
```

---

## ملاحظات مهمة

✅ **تم إضافة جميع العلاقات الضرورية**

- جميع العلاقات الأساسية (One-to-Many, Many-to-One)
- العلاقات المركبة (HasManyThrough, BelongsToThrough)
- العلاقات المتعددة (Many-to-Many)

📊 **نوع العلاقات المستخدمة:**

- `BelongsTo`: من جدول إلى جدول آخر (1:1 أو N:1)
- `HasMany`: من جدول إلى عدة صفوف (1:N)
- `BelongsToMany`: علاقة متعددة عبر جدول وسيط
- `HasManyThrough`: الوصول إلى البيانات عبر جدول وسيط
- `BelongsToThrough`: الانتماء عبر جداول وسيطة

🔗 **الفوائد:**

- سهولة الوصول إلى البيانات المرتبطة
- تحسين الكود والقابلية للقراءة
- دعم العمليات المعقدة (Eager Loading)
- تقليل عدد الاستعلامات إلى قاعدة البيانات
