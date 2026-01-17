<?php

/**
 * أمثلة عملية على استخدام العلاقات
 * Practical Examples of Using Relationships
 */

// ============================================
// 1. أمثلة على User Model
// ============================================

// مثال 1.1: الحصول على جميع الزيارات لمستخدم معين
User::find(1)->visits; // جميع الزيارات

// مثال 1.2: الحصول على الزيارات مع تفاصيل المدرسة
User::find(1)->visits()
    ->with('school')
    ->get();

// مثال 1.3: الحصول على الزيارات في عام دراسي معين
User::find(1)->visits()
    ->where('academic_year_id', 1)
    ->get();

// مثال 1.4: الحصول على جميع المدارس التي ينسقها مستخدم
User::find(1)->schoolsAsCoordinator; // كمنسق
User::find(1)->schoolsAsPrincipal;   // كمدير
User::find(1)->allAssociatedSchools; // جميع المدارس

// مثال 1.5: الحصول على جميع الأحداث التي نظمها مستخدم
User::find(1)->workEvents; // جميع الأحداث

// مثال 1.6: الحصول على المرفقات التي رفعها المستخدم
User::find(1)->uploadedAttachments; // جميع المرفقات


// ============================================
// 2. أمثلة على School Model
// ============================================

// مثال 2.1: الحصول على جميع الزيارات في مدرسة معينة
School::find(1)->visits; // جميع الزيارات

// مثال 2.2: الحصول على الزيارات مع تفاصيل المستخدم والعام الدراسي
School::find(1)->visits()
    ->with(['user', 'academicYear'])
    ->get();

// مثال 2.3: الحصول على المنسق والمدير للمدرسة
$school = School::find(1);
$coordinator = $school->coordinator; // المنسق
$principal = $school->principal;     // المدير

// مثال 2.4: الحصول على دورات البرامج في المدرسة
School::find(1)->programCycles; // جميع البرامج

// مثال 2.5: الحصول على جميع الأحداث في المدرسة
School::find(1)->workEvents; // جميع الأحداث


// ============================================
// 3. أمثلة على Visit Model
// ============================================

// مثال 3.1: الحصول على تفاصيل الزيارة الكاملة
$visit = Visit::find(1);
$visit->user;           // المستخدم
$visit->school;         // المدرسة
$visit->academicYear;   // السنة الدراسية
$visit->programCycle;   // دورة البرنامج
$visit->attachments;    // المرفقات

// مثال 3.2: تحميل الزيارات مع جميع البيانات المرتبطة
Visit::with(['user', 'school', 'academicYear', 'programCycle', 'attachments'])->get();

// مثال 3.3: البحث عن الزيارات في مدرسة معينة
Visit::where('school_id', 1)->with('user')->get();

// مثال 3.4: الحصول على الزيارات لمستخدم معين في عام دراسي معين
Visit::where('user_id', 1)
    ->where('academic_year_id', 1)
    ->get();


// ============================================
// 4. أمثلة على VisitAttachment Model
// ============================================

// مثال 4.1: الحصول على تفاصيل المرفق
$attachment = VisitAttachment::find(1);
$attachment->visit;    // الزيارة الأصلية
$attachment->uploadedBy; // المستخدم الذي رفعه

// مثال 4.2: الحصول على جميع المرفقات لزيارة معينة
Visit::find(1)->attachments; // جميع المرفقات

// مثال 4.3: الحصول على المرفقات مع معلومات من رفعها
Visit::find(1)->attachments()
    ->with('uploadedBy')
    ->get();


// ============================================
// 5. أمثلة على WorkEvent Model
// ============================================

// مثال 5.1: الحصول على تفاصيل الحدث
$event = WorkEvent::find(1);
$event->user;           // المستخدم
$event->academicYear;   // السنة الدراسية
$event->programCycle;   // دورة البرنامج

// مثال 5.2: الحصول على جميع الأحداث لمستخدم معين
User::find(1)->workEvents; // جميع أحدثه

// مثال 5.3: الحصول على الأحداث في عام دراسي معين
AcademicYear::find(1)->workEvents; // جميع الأحداث


// ============================================
// 6. أمثلة على Program Model
// ============================================

// مثال 6.1: الحصول على جميع دورات البرنامج
Program::find(1)->programCycles; // جميع الدورات

// مثال 6.2: الحصول على جميع المؤشرات للبرنامج
Program::find(1)->indicators; // جميع المؤشرات

// مثال 6.3: الحصول على جميع الزيارات المرتبطة بالبرنامج
Program::find(1)->visits; // جميع الزيارات

// مثال 6.4: الحصول على جميع الأحداث المرتبطة بالبرنامج
Program::find(1)->workEvents; // جميع الأحداث


// ============================================
// 7. أمثلة على ProgramCycle Model
// ============================================

// مثال 7.1: الحصول على تفاصيل دورة البرنامج
$cycle = ProgramCycle::find(1);
$cycle->program;        // البرنامج الأصلي
$cycle->academicYear;   // السنة الدراسية
$cycle->indicators;     // المؤشرات
$cycle->schools;        // المدارس
$cycle->visits;         // الزيارات
$cycle->workEvents;     // الأحداث

// مثال 7.2: الحصول على المدارس المرتبطة بدورة البرنامج
ProgramCycle::find(1)->schools; // جميع المدارس


// ============================================
// 8. أمثلة على Sector Model
// ============================================

// مثال 8.1: الحصول على جميع المدارس في القطاع
Sector::find(1)->schools; // جميع المدارس

// مثال 8.2: الحصول على جميع المستخدمين في القطاع
Sector::find(1)->users; // جميع المستخدمين

// مثال 8.3: الحصول على جميع الزيارات في القطاع (عبر المدارس)
Sector::find(1)->visits; // جميع الزيارات

// مثال 8.4: الحصول على دورات البرامج في القطاع
Sector::find(1)->programCycles; // جميع الدورات


// ============================================
// 9. أمثلة على AcademicYear Model
// ============================================

// مثال 9.1: الحصول على دورات البرامج للعام الدراسي
AcademicYear::find(1)->programCycles; // جميع الدورات

// مثال 9.2: الحصول على جميع المؤشرات للعام الدراسي
AcademicYear::find(1)->programCycleIndicators; // جميع المؤشرات

// مثال 9.3: الحصول على جميع الزيارات للعام الدراسي
AcademicYear::find(1)->visits; // جميع الزيارات

// مثال 9.4: الحصول على جميع الأحداث للعام الدراسي
AcademicYear::find(1)->workEvents; // جميع الأحداث


// ============================================
// 10. أمثلة متقدمة - البحث والتصفية
// ============================================

// مثال 10.1: البحث عن المدارس التي بها زيارات
$schools = School::whereHas('visits')->get();

// مثال 10.2: البحث عن المستخدمين الذين لديهم زيارات
$users = User::whereHas('visits')->get();

// مثال 10.3: عد الزيارات لمدرسة معينة
$visitCount = School::find(1)->visits()->count();

// مثال 10.4: الحصول على آخر الزيارات
$latestVisits = Visit::latest('visit_date')->get();

// مثال 10.5: البحث عن الزيارات بين تاريخين
$visits = Visit::whereBetween('visit_date', ['2026-01-01', '2026-01-31'])->get();

// مثال 10.6: تجميع البيانات
$visitsBySchool = Visit::groupBy('school_id')
    ->selectRaw('school_id, count(*) as total')
    ->get();


// ============================================
// 11. أمثلة على Eager Loading
// ============================================

// مثال 11.1: تحميل البيانات المرتبطة بكفاءة
$users = User::with(['visits', 'workEvents', 'sector'])->get();

// مثال 11.2: تحميل البيانات المتسلسلة
$visits = Visit::with(['user', 'school', 'academicYear', 'programCycle', 'attachments'])->get();

// مثال 11.3: تحميل البيانات المرتبطة بشروط
$users = User::with([
    'visits' => function ($query) {
        $query->where('visit_type', 'زيارة فنية');
    }
])->get();

// مثال 11.4: تحميل البيانات مع الحد من الحقول
$visits = Visit::with([
    'user:id,name,email',
    'school:id,name'
])->get();


// ============================================
// 12. أمثلة على العلاقات المعكوسة (Inverse)
// ============================================

// مثال 12.1: الحصول على الزيارة من خلال المرفق
$attachment = VisitAttachment::find(1);
$visit = $attachment->visit;

// مثال 12.2: الحصول على المستخدم من خلال الزيارة
$visit = Visit::find(1);
$user = $visit->user;

// مثال 12.3: الحصول على البرنامج من خلال الدورة
$cycle = ProgramCycle::find(1);
$program = $cycle->program;


// ============================================
// 13. أمثلة على المعالجة والتحويل
// ============================================

// مثال 13.1: تحويل البيانات المرتبطة
$user = User::with('visits')->find(1);
$visitCount = $user->visits->count();
$visitDates = $user->visits->pluck('visit_date');

// مثال 13.2: التصفية على البيانات المحملة
$visits = Visit::with('user')
    ->get()
    ->filter(function ($visit) {
        return $visit->user->sector_id == 1;
    });

// مثال 13.3: تجميع البيانات
$grouped = User::with('visits')
    ->get()
    ->groupBy('sector_id');


// ============================================
// 14. أمثلة على الأداء
// ============================================

// مثال 14.1: تجنب N+1 problem
// ❌ خطأ: سيؤدي إلى استعلامات عديدة
$users = User::all();
foreach ($users as $user) {
    echo $user->visits->count(); // استعلام لكل مستخدم!
}

// ✅ الصحيح: استعلام واحد فقط
$users = User::withCount('visits')->get();
foreach ($users as $user) {
    echo $user->visits_count; // بدون استعلام إضافي
}

// مثال 14.2: استخدام Lazy Loading بحذر
$visit = Visit::find(1);
$user = $visit->user; // استعلام إضافي
$school = $visit->school; // استعلام إضافي

// ✅ أفضل: Eager Loading
$visit = Visit::with(['user', 'school'])->find(1);
$user = $visit->user; // لا استعلام إضافي
$school = $visit->school; // لا استعلام إضافي
