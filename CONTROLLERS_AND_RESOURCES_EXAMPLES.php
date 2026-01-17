<?php

/**
 * أمثلة على استخدام العلاقات في Controllers و Resources
 * Examples of Using Relationships in Controllers and Resources
 */

// ============================================
// Controllers Examples
// ============================================

namespace App\Http\Controllers;

use App\Models\{User, School, Visit, Program, AcademicYear};

class VisitController extends Controller
{
    /**
     * مثال 1: عرض جميع الزيارات مع البيانات المرتبطة
     */
    public function index()
    {
        // استخدام Eager Loading لتحسين الأداء
        $visits = Visit::with([
            'user' => function ($query) {
                $query->select('id', 'name', 'email');
            },
            'school' => function ($query) {
                $query->select('id', 'name', 'ministry_code');
            },
            'academicYear' => function ($query) {
                $query->select('id', 'name');
            },
            'programCycle' => function ($query) {
                $query->select('id', 'term');
            },
            'attachments' => function ($query) {
                $query->select('id', 'visit_id', 'original_name', 'file_size');
            }
        ])->paginate(20);

        return view('visits.index', ['visits' => $visits]);
    }

    /**
     * مثال 2: عرض الزيارات لمستخدم معين
     */
    public function userVisits(User $user)
    {
        $visits = $user->visits()
            ->with(['school:id,name', 'academicYear:id,name'])
            ->latest('visit_date')
            ->paginate(15);

        return view('visits.user-visits', [
            'user' => $user,
            'visits' => $visits
        ]);
    }

    /**
     * مثال 3: عرض الزيارات في مدرسة معينة
     */
    public function schoolVisits(School $school)
    {
        $visits = $school->visits()
            ->with(['user:id,name,email', 'academicYear:id,name'])
            ->where('visit_date', '>=', now()->subMonths(3))
            ->latest('visit_date')
            ->get();

        $totalVisits = $school->visits()->count();
        $averageVisitsPerMonth = round($totalVisits / 3, 2);

        return view('visits.school-visits', [
            'school' => $school,
            'visits' => $visits,
            'totalVisits' => $totalVisits,
            'averageVisitsPerMonth' => $averageVisitsPerMonth
        ]);
    }

    /**
     * مثال 4: إنشاء زيارة جديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'user_id' => 'required|exists:users,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'program_cycle_id' => 'nullable|exists:program_cycles,id',
            'visit_date' => 'required|date',
            'visit_type' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $visit = Visit::create($validated);

        // تحميل البيانات المرتبطة
        $visit->load(['user', 'school', 'academicYear']);

        return redirect()->route('visits.show', $visit)
            ->with('success', 'تم إنشاء الزيارة بنجاح');
    }

    /**
     * مثال 5: عرض تفاصيل الزيارة
     */
    public function show(Visit $visit)
    {
        $visit->load([
            'user',
            'school',
            'academicYear',
            'programCycle',
            'attachments.uploadedBy'
        ]);

        return view('visits.show', ['visit' => $visit]);
    }

    /**
     * مثال 6: إضافة مرفق للزيارة
     */
    public function addAttachment(Request $request, Visit $visit)
    {
        $file = $request->file('file');

        $attachment = $visit->attachments()->create([
            'file_path' => $file->store('attachments'),
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'تم رفع المرفق بنجاح',
            'attachment' => $attachment
        ]);
    }
}

class SchoolController extends Controller
{
    /**
     * مثال 7: عرض معلومات المدرسة مع الإحصائيات
     */
    public function show(School $school)
    {
        $school->load([
            'sector',
            'coordinator',
            'principal',
            'programCycles',
        ]);

        $statistics = [
            'total_visits' => $school->visits()->count(),
            'total_attachments' => $school->visits()
                ->with('attachments')
                ->get()
                ->sum(fn($visit) => $visit->attachments->count()),
            'recent_visits' => $school->visits()
                ->with('user:id,name')
                ->latest('visit_date')
                ->limit(5)
                ->get(),
            'visit_types' => $school->visits()
                ->selectRaw('visit_type, count(*) as count')
                ->groupBy('visit_type')
                ->get()
        ];

        return view('schools.show', [
            'school' => $school,
            'statistics' => $statistics
        ]);
    }

    /**
     * مثال 8: عرض جميع المدارس في قطاع معين
     */
    public function sectorSchools(Sector $sector)
    {
        $schools = $sector->schools()
            ->withCount('visits', 'programCycles')
            ->with(['coordinator:id,name', 'principal:id,name'])
            ->get();

        return view('sectors.schools', [
            'sector' => $sector,
            'schools' => $schools
        ]);
    }
}

class UserController extends Controller
{
    /**
     * مثال 9: عرض ملف المستخدم الشخصي
     */
    public function profile(User $user)
    {
        $user->load([
            'sector',
            'visits' => fn($q) => $q->latest('visit_date')->limit(10),
            'workEvents' => fn($q) => $q->latest('event_date')->limit(10),
            'schoolsAsCoordinator',
            'schoolsAsPrincipal',
        ]);

        $statistics = [
            'total_visits' => $user->visits()->count(),
            'total_events' => $user->workEvents()->count(),
            'visit_types' => $user->visits()
                ->selectRaw('visit_type, count(*) as count')
                ->groupBy('visit_type')
                ->get(),
            'recent_attachments' => $user->uploadedAttachments()
                ->latest('created_at')
                ->limit(5)
                ->get()
        ];

        return view('users.profile', [
            'user' => $user,
            'statistics' => $statistics
        ]);
    }

    /**
     * مثال 10: البحث عن المستخدمين النشطين
     */
    public function activeUsers()
    {
        $users = User::whereHas(
            'visits',
            fn($q) =>
            $q->where('visit_date', '>=', now()->subMonth())
        )->withCount([
            'visits' => fn($q) => $q->where('visit_date', '>=', now()->subMonth()),
            'workEvents' => fn($q) => $q->where('event_date', '>=', now()->subMonth())
        ])->get();

        return view('users.active', ['users' => $users]);
    }
}

class ReportController extends Controller
{
    /**
     * مثال 11: تقرير الزيارات الشهري
     */
    public function monthlyReport(AcademicYear $academicYear, $month)
    {
        $visits = $academicYear->visits()
            ->whereMonth('visit_date', $month)
            ->with(['user:id,name', 'school:id,name', 'programCycle:id,term'])
            ->get()
            ->groupBy('school_id');

        return view('reports.monthly', [
            'academicYear' => $academicYear,
            'month' => $month,
            'visits' => $visits,
            'summary' => [
                'total_visits' => $visits->sum(fn($group) => count($group)),
                'schools_visited' => count($visits),
                'visit_types' => Visit::whereMonth('visit_date', $month)
                    ->selectRaw('visit_type, count(*) as count')
                    ->groupBy('visit_type')
                    ->get()
            ]
        ]);
    }

    /**
     * مثال 12: تقرير البرنامج الشامل
     */
    public function programReport(Program $program)
    {
        $program->load([
            'programCycles.schools',
            'programCycles.indicators',
            'visits',
            'workEvents'
        ]);

        $report = [
            'program' => $program,
            'total_cycles' => $program->programCycles()->count(),
            'total_schools' => $program->programCycles()
                ->with('schools')
                ->get()
                ->pluck('schools')
                ->flatten()
                ->unique('id')
                ->count(),
            'total_visits' => $program->visits()->count(),
            'total_events' => $program->workEvents()->count(),
            'indicators_summary' => $program->indicators()
                ->selectRaw('title, target_value, actual_value')
                ->get()
        ];

        return view('reports.program', $report);
    }
}

// ============================================
// Resources Examples (API)
// ============================================

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitResource extends JsonResource
{
    /**
     * مثال 13: تحويل الزيارة إلى JSON
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'visit_date' => $this->visit_date->format('Y-m-d'),
            'visit_type' => $this->visit_type,
            'objective' => $this->objective,
            'notes' => $this->notes,
            'recommendations' => $this->recommendations,

            // البيانات المرتبطة
            'user' => new UserResource($this->whenLoaded('user')),
            'school' => new SchoolResource($this->whenLoaded('school')),
            'academic_year' => new AcademicYearResource($this->whenLoaded('academicYear')),
            'program_cycle' => new ProgramCycleResource($this->whenLoaded('programCycle')),
            'attachments' => VisitAttachmentResource::collection($this->whenLoaded('attachments')),

            // الروابط
            'links' => [
                'self' => route('api.visits.show', $this->id),
                'school' => route('api.schools.show', $this->school_id),
                'user' => route('api.users.show', $this->user_id)
            ]
        ];
    }
}

class SchoolResource extends JsonResource
{
    /**
     * مثال 14: تحويل المدرسة إلى JSON مع البيانات المرتبطة
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ministry_code' => $this->ministry_code,
            'gender' => $this->gender,
            'stage' => $this->stage,
            'status' => $this->status,

            // البيانات المرتبطة
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'coordinator' => new UserResource($this->whenLoaded('coordinator')),
            'principal' => new UserResource($this->whenLoaded('principal')),
            'program_cycles' => ProgramCycleResource::collection($this->whenLoaded('programCycles')),

            // العد
            'visits_count' => $this->when(
                isset($this->visits_count),
                $this->visits_count
            ),
            'program_cycles_count' => $this->when(
                isset($this->program_cycles_count),
                $this->program_cycles_count
            )
        ];
    }
}

class UserResource extends JsonResource
{
    /**
     * مثال 15: تحويل المستخدم إلى JSON
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'status' => $this->status,

            // البيانات المرتبطة
            'sector' => new SectorResource($this->whenLoaded('sector')),
            'visits' => VisitResource::collection($this->whenLoaded('visits')),
            'work_events' => WorkEventResource::collection($this->whenLoaded('workEvents')),
            'coordinated_schools' => SchoolResource::collection($this->whenLoaded('schoolsAsCoordinator')),
            'principal_schools' => SchoolResource::collection($this->whenLoaded('schoolsAsPrincipal')),

            // العد
            'visits_count' => $this->when(
                isset($this->visits_count),
                $this->visits_count
            ),
            'work_events_count' => $this->when(
                isset($this->work_events_count),
                $this->work_events_count
            )
        ];
    }
}

// ============================================
// Query Examples
// ============================================

/**
 * مثال 16: استعلامات معقدة باستخدام العلاقات
 */
class AdvancedQueryExamples
{
    /**
     * البحث عن المدارس التي لم تزرها أي مستخدم
     */
    public function schoolsWithoutVisits()
    {
        return School::whereDoesntHave('visits')->get();
    }

    /**
     * البحث عن المستخدمين الذين أجروا أكثر من 5 زيارات
     */
    public function activeUsers()
    {
        return User::has('visits', '>=', 5)->withCount('visits')->get();
    }

    /**
     * البحث عن الزيارات في برامج معينة
     */
    public function visitsForProgram(Program $program)
    {
        return $program->visits()
            ->with('school:id,name')
            ->latest('visit_date')
            ->get();
    }

    /**
     * عد الزيارات حسب نوع الزيارة
     */
    public function visitStatistics()
    {
        return Visit::selectRaw('visit_type, count(*) as total, avg(DATEDIFF(CURDATE(), visit_date)) as avg_days_ago')
            ->with('school:id,name')
            ->groupBy('visit_type')
            ->get();
    }
}
