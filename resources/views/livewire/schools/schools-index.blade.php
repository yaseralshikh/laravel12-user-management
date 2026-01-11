<div>
   <div class="p-3">
        {{-- for show Create modal --}}
        <livewire:schools.school-create />

        {{-- for show Edit modal --}}
        <livewire:schools.school-edit />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-school" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف مدرسة؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف هذه المدرسة.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف المدرسة</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إنشاء مدرسة --}}
                <flux:modal.trigger name="create-school">
                    @permission('schools-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة مدرسة
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة مدرسة
                        </flux:button>
                    @endpermission
                </flux:modal.trigger>

                <div class="flex items-center gap-2">
                    {{-- زر تصدير Excel --}}
                    <x-button wire:click="exportExcel" color="success" class="p-2 w-10 h-10 flex items-center justify-center" title="تصدير Excel">
                        <flux:icon.arrow-down-on-square variant="solid" class="w-5 h-5 text-green-600" />
                    </x-button>

                    {{-- زر تصدير PDF --}}
                    <x-button wire:click="exportPdf" color="success" class="p-2 w-10 h-10 flex items-center justify-center" title="تصدير PDF">
                        <flux:icon.document-text variant="solid" class="w-5 h-5 text-red-600" />
                    </x-button>

                    {{-- total schools --}}
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي المدارس: ({{ $schools->total() }})</span>
                </div>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث في المدارس" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- فلاتر التصنيف --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-7 gap-3 mb-4">
            <flux:select wire:model.live="genderFilter" label="نوع المدرسة">
                <option value="">كل الأنواع</option>
                @foreach($genders as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="statusFilter" label="الحالة">
                <option value="">كل الحالات</option>
                @foreach($statuses as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="schoolTypeFilter" label="نوع التعليم">
                <option value="">كل أنواع التعليم</option>
                @foreach($schoolTypes as $key => $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="buildingTypeFilter" label="نوع المبنى">
                <option value="">كل أنواع المباني</option>
                @foreach($buildingTypes as $key => $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="sectorFilter" label="القطاع التعليمي">
                <option value="">كل القطاعات</option>
                @foreach($educationalSectors as $key => $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="stageFilter" label="المراحل الدراسية">
                <option value="">كل المراحل</option>
                @foreach($stageOptions as $key => $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model.live="complexFilter" label="مجمع">
                <option value="">كل المدارس</option>
                <option value="1">ضمن مجمع</option>
                <option value="0">مستقلة</option>
            </flux:select>
        </div>

        {{-- جدول عرض المدارس --}}
       <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
           <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
               <thead class="text-xs uppercase bg-gray-500/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                <tr>
                   <th scope="col" class="px-6 py-3">م</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                        اسم المدرسة
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('ministry_code')">
                        الرمز الوزاري
                        @if($sortField === 'ministry_code')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3">النوع</th>
                    <th scope="col" class="px-6 py-3">المراحل</th>
                    <th scope="col" class="px-6 py-3">مجمع</th>
                    <th scope="col" class="px-6 py-3">نوع التعليم</th>
                    <th scope="col" class="px-6 py-3">الحالة</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        تاريخ الإنشاء
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                   <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
               </tr>
               </thead>
               <tbody>
                @forelse ($schools as $school)                    
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="school-{{ $school->id }}">
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $schools->firstItem() + $loop->index  }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->name }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->ministry_code }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ config('schools.genders')[$school->gender] ?? $school->gender }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->stage }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                            <flux:badge color="{{ $school->is_complex ? 'blue' : 'gray' }}">{{ $school->is_complex ? 'ضمن مجمع' : 'مستقلة' }}</flux:badge>
                        </td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->school_type }}</td>
                        <td class="px-6 py-2 text-gray-700">
                            @php
                                $statusLabel = config('schools.statuses')[$school->status] ?? $school->status;
                                $statusColor = $school->status === 'active' ? 'green' : ($school->status === 'inactive' ? 'red' : 'yellow');
                            @endphp
                            <flux:badge color="{{ $statusColor }}">{{ $statusLabel }}</flux:badge>
                        </td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-2 space-x-1">
                            @role('admin|superadmin')
                                @permission('schools-update')
                                    <flux:button variant="primary" size="sm" wire:click="edit({{ $school->id }})">تعديل</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>تعديل</flux:button>
                                @endpermission

                                @permission('schools-delete')
                                    <flux:button variant="danger" size="sm" wire:click="delete({{ $school->id }})">حذف</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>حذف</flux:button>
                                @endpermission
                            @else
                                <flux:button variant="subtle" size="sm" disabled>تعديل</flux:button>
                                <flux:button variant="subtle" size="sm" disabled>حذف</flux:button>
                            @endrole
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
                {{ $schools->links() }}
            </div>
       </div>
   </div> 
</div>

@push('scripts')
<script>
    //console.log('Script خاص بصفحة المدارس');
</script>
@endpush
