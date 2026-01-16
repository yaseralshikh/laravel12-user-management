<div>
    <div class="p-3">
        <flux:heading
            size="xl"
            class="mb-6 text-center font-bold text-sky-600 dark:text-gray-200 tracking-wide">
            إدارة دورات البرامج
        </flux:heading>
        <div class="mx-auto mb-6 h-1 w-full rounded bg-sky-500 dark:bg-gray-500"></div>

        {{-- for show Create modal --}}
        <livewire:program-cycles.program-cycle-create />

        {{-- for show Edit modal --}}
        <livewire:program-cycles.program-cycle-edit />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-program-cycle" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف دورة البرنامج؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف دورة البرنامج.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف الدورة</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إنشاء دورة برنامج --}}
                <flux:modal.trigger name="create-program-cycle">
                    @permission('program_cycles-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء دورة برنامج
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء دورة برنامج
                        </flux:button>
                    @endpermission
                </flux:modal.trigger>

                {{-- total program cycles --}}
                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي الدورات: ({{ $programCycles->total() }})</span>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث عن دورة برنامج" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- جدول عرض دورات البرامج --}}
        <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase bg-sky-500/10 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                    <th scope="col" class="px-6 py-3">رقم</th>
                    <th scope="col" class="px-6 py-3">البرنامج</th>
                    <th scope="col" class="px-6 py-3">السنة الأكاديمية</th>
                    <th scope="col" class="px-6 py-3">الفصل</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                        الحالة
                        @if($sortField === 'status')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3">تاريخ البداية</th>
                    <th scope="col" class="px-6 py-3">تاريخ النهاية</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        تاريخ الإنشاء
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
                </thead>
                <tbody>
                    @forelse ($programCycles as $cycle)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="cycle-{{ $cycle->id }}">
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $programCycles->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->program->name }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->academicYear->name }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->term ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700">
                                <flux:badge 
                                    color="{{ match($cycle->status) {
                                        'in_progress' => 'blue',
                                        'completed' => 'green',
                                        'suspended' => 'yellow',
                                        'canceled' => 'red',
                                        default => 'gray'
                                    } }}">
                                    {{ match($cycle->status) {
                                        'in_progress' => 'جاري',
                                        'completed' => 'مكتمل',
                                        'suspended' => 'معلق',
                                        'canceled' => 'ملغى',
                                        default => 'غير معروف'
                                    } }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->start_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->end_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $cycle->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-2 space-x-1">
                                @permission('program_cycles-update')
                                    <flux:button variant="primary" size="sm" wire:click="edit({{ $cycle->id }})">تعديل</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>تعديل</flux:button>
                                @endpermission

                                @permission('program_cycles-delete')
                                    <flux:button variant="danger" size="sm" wire:click="delete({{ $cycle->id }})">حذف</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>حذف</flux:button>
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-gray-700 dark:text-gray-300 py-4">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="m-4">
                {{ $programCycles->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    //console.log('Script خاص بهذه الصفحة فقط');
</script>
@endpush
