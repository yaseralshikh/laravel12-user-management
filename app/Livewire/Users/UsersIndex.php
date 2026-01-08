<?php

namespace App\Livewire\Users;

use Flux;
use Mpdf\Mpdf;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Exports\UsersExport;
use Livewire\WithPagination;

class UsersIndex extends Component
{
    use WithPagination;

    public $userId;
    public $term = '';
    public string $sortField = 'id'; // الحقل الافتراضي
    public string $sortDirection = 'asc'; // الترتيب الافتراضي

    public function updatedTerm()
    {
        $this->resetPage(); // يعيدك للصفحة الأولى عند تغيير قيمة term
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

    public function exportExcel()
    {
        // استعلام لجلب البيانات المطلوبة
        $data = User::query()
            ->when($this->term, fn($q) =>
                $q->where('name', 'like', '%' . $this->term . '%')
                ->orWhere('email', 'like', '%' . $this->term . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->get();

        // إنشاء الملف وإرجاع اسمه
        $export = new UsersExport();
        $file = $export->export($data); // نمرر البيانات هنا
        // إظهار رسالة نجاح
        $this->dispatch('showSuccessAlert', message: 'تم إنشاء الملف بنجاح!');

        return response()->download(public_path($file))->deleteFileAfterSend(true);
    }

    public function exportPdf()
    {
        $data = User::query()
                ->when($this->term, fn($q) =>
                    $q->where('name', 'like', '%' . $this->term . '%')
                    ->orWhere('email', 'like', '%' . $this->term . '%')
                )
                ->orderBy($this->sortField, $this->sortDirection)
                ->latest('created_at')
                ->get();

        $html = view('exports.users', compact('data'))->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans', // يدعم العربي مباشرة
        ]);

        $mpdf->WriteHTML($html);

        $fileName = 'users_' . now()->format('Ymd_His') . '.pdf';
        $filePath = public_path($fileName);
        $mpdf->Output($filePath, \Mpdf\Output\Destination::FILE);

        $this->dispatch('showSuccessAlert', message: 'تم إنشاء ملف PDF بنجاح!');

        return response()->download($filePath)->deleteFileAfterSend(true);
    }      
        
    public function getUsersProperty()
    {
        $users = User::query()
            ->when($this->term, fn($q) =>
                $q->where('name', 'like', '%' . $this->term . '%')
                ->orWhere('email', 'like', '%' . $this->term . '%')
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->latest('created_at')
            ->paginate(10);

        return $users;
    }

    public function render()
    {
        return view('livewire.users.users-index', [
            'users' => $this->users,
        ]);
    }
}
