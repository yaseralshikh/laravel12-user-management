<div>
    <div class="p-3">
        <flux:heading
            size="xl"
            class="mb-6 text-center font-bold text-sky-600 dark:text-gray-200 tracking-wide">
            @lang('messages.academic_years_management', ['management' => 'إدارة '])    
        </flux:heading>
        <div class="mx-auto mb-6 h-1 w-full rounded bg-sky-500 dark:bg-gray-500"></div>

        {{-- for show Create modal --}}
        <livewire:academic-years.academic-year-create />

        {{-- for show Edit modal --}}
        <livewire:academic-years.academic-year-edit />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-academic-year" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف السنة الأكاديمية؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف هذه السنة الأكاديمية.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف السنة</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إنشاء سنة أكاديمية --}}
                <flux:modal.trigger name="create-academic-year">
                    @permission('academic_years-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء سنة دراسية
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء سنة دراسية
                        </flux:button>
                    @endpermission
                </flux:modal.trigger>

                {{-- total academic years --}}
                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي السنوات الدراسية: ({{ $academicYears->total() }})</span>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث عن سنة دراسية" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- جدول عرض السنوات الأكاديمية --}}
        <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase bg-sky-500/10 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                    <th scope="col" class="px-6 py-3">رقم</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                        الاسم
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3">تاريخ البداية</th>
                    <th scope="col" class="px-6 py-3">تاريخ النهاية</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                        الحالة
                        @if($sortField === 'status')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        تاريخ الإنشاء
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
                </thead>
                <tbody>
                    @forelse ($academicYears as $academicYear)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="academic-year-{{ $academicYear->id }}">
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $academicYears->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $academicYear->name }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $academicYear->starts_on?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $academicYear->ends_on?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700">
                                <flux:badge 
                                    color="{{ $academicYear->status == 'active' ? 'green' : 'red' }}">
                                    {{ $academicYear->status == 'active' ? 'نشط' : 'غير نشط' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $academicYear->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-2 space-x-1">
                                @permission('academic_years-update')
                                    <flux:button variant="primary" size="sm" wire:click="edit({{ $academicYear->id }})">تعديل</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>تعديل</flux:button>
                                @endpermission

                                @permission('academic_years-delete')
                                    <flux:button variant="danger" size="sm" wire:click="delete({{ $academicYear->id }})">حذف</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>حذف</flux:button>
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-700 dark:text-gray-300 py-4">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="m-4">
                {{ $academicYears->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    //console.log('Script خاص بهذه الصفحة فقط');
</script>
@endpush
