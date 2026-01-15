<?php

namespace App\Livewire\Users;

use \Mpdf\Output\Destination;
use App\Exports\UsersExport;
use App\Models\Role;
use App\Models\Sector;
use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Mpdf\Mpdf;

class UsersIndex extends Component
{
    use WithPagination;

    public $userId;
    public $term = '';
    public string $sortField = 'id'; // الحقل الافتراضي
    public string $sortDirection = 'asc'; // الترتيب الافتراضي

    // Filter properties
    public $roleFilter = '';
    public $sectorFilter = '';
    public $dateFilter = '';

    public function updatedTerm()
    {
        $this->resetPage(); // يعيدك للصفحة الأولى عند تغيير قيمة term
    }

    public function updatedRoleFilter()
    {
        $this->resetPage();
    }

    public function updatedSectorFilter()
    {
        $this->resetPage();
    }

    public function updatedDateFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }


    #[On('reloadUsers')]
    public function reloadPage()
    {
        $this->resetPage(); // يعيد تحميل الصفحة الأولى بعد التحديث
    }

    public function edit($userId)
    {
        if ($user = User::with('roles')->find($userId)) {
            $this->dispatch('openEditModal', ['user' => $user]);
        }
    }

    public function delete($userId)
    {
        $this->userId = $userId;
        Flux::modal('delete-user')->show();
    }

    public function destroy()
    {
        $user = User::findOrFail($this->userId);
        // فصل الصلاحيات المرتبطة بالمستخدم
        $user->roles()->detach();
        // حذف المستخدم
        $user->delete();

        Flux::modal('delete-user')->close();
        $this->dispatch('showSuccessAlert', message: 'تم حذف المستخدم بنجاح');
        $this->resetPage(); // في حال تم الحذف في آخر عنصر في الصفحة
    }

    /**
     * دالة مساعدة لتطبيق جميع الفلاتر والبحث
     */
    private function getFilteredQuery()
    {
        $query = User::query()
            ->where('name', '!=', 'Super Admin'); // استثناء المستخدم الأول (superadmin)

        // Search filter
        if ($this->term) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->term . '%')
                    ->orWhere('email', 'like', '%' . $this->term . '%');
            });
        }

        // Role filter
        if ($this->roleFilter) {
            $query->whereHas('roles', function ($q) {
                $q->where('name', $this->roleFilter);
            });
        }

        // Sector filter
        if ($this->sectorFilter) {
            $query->where('sector_id', $this->sectorFilter);
        }

        // Date filter
        if ($this->dateFilter) {
            $now = now();
            match ($this->dateFilter) {
                'today' => $query->whereDate('created_at', $now->toDateString()),
                'week' => function ($q) use ($now) {
                    // حساب الأحد (بداية الأسبوع)
                    $daysFromSunday = $now->dayOfWeek; // عدد الأيام من الأحد
                    $sunday = $now->copy()->subDays($daysFromSunday)->startOfDay();
                    $saturday = $sunday->copy()->addDays(6)->endOfDay();
                    return $q->whereBetween('created_at', [$sunday, $saturday]);
                },
                'month' => $query->whereYear('created_at', $now->year)
                    ->whereMonth('created_at', $now->month),
                'year' => $query->whereYear('created_at', $now->year),
                default => null
            };
        }

        return $query
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at');
    }

    public function exportExcel()
    {
        // استعلام لجلب البيانات المطلوبة مع تطبيق جميع الفلاتر
        $data = $this->getFilteredQuery()->get();

        // إنشاء الملف وإرجاع اسمه
        $export = new UsersExport();
        $file = $export->export($data); // نمرر البيانات هنا
        // إظهار رسالة نجاح
        $this->dispatch('showSuccessAlert', message: 'تم إنشاء الملف بنجاح!');

        return response()->download(public_path($file))->deleteFileAfterSend(true);
    }

    public function exportPdf()
    {
        $data = $this->getFilteredQuery()->get();

        $html = view('exports.users', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans', // يدعم العربي مباشرة
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'users_' . now()->format('Ymd_His') . '.pdf';
        $filePath = public_path($fileName);
        $mpdf->Output($filePath, Destination::FILE);

        $this->dispatch('showSuccessAlert', message: 'تم إنشاء ملف PDF بنجاح!');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function getUsersProperty()
    {
        return $this->getFilteredQuery()->paginate(10);
    }

    public function render()
    {
        $roles = Role::where('name', '!=', 'superadmin')->get();

        return view('livewire.users.users-index', [
            'users' => $this->users,
            'roles' => $roles,
            'sectors' => Sector::all(), // جلب جميع القطاعات التعليمية
        ]);
    }
}
