<div>
   <div class="p-3">
        <flux:heading
            size="xl"
            class="mb-6 text-center font-bold text-sky-600 dark:text-gray-200 tracking-wide">
            @lang('messages.schools_management',['management' => 'إدارة '])
        </flux:heading>
        <div class="mx-auto mb-6 h-1 w-full rounded bg-sky-500 dark:bg-gray-500"></div>

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
                @foreach($sectors as $sector)
                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
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

        <style>
            [x-cloak] { display:none !important; }
        </style>

        {{-- جدول عرض المدارس --}}
       <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800" x-data="{ expandedRows: {} }">
           <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
               <thead class="text-xs uppercase bg-sky-500/10 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                <tr>
                   <th scope="col" class="px-6 py-3 w-10"></th>
                   <th scope="col" class="px-6 py-3">م</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                        اسم المدرسة
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('gender')">
                        النوع
                        @if($sortField === 'gender')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('stage')">
                        المراحل
                        @if($sortField === 'stage')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif                        
                    </th>
                    <th scope="col" class="px-6 py-3">مجمع</th>
                    <th scope="col" class="px-6 py-3">الحالة</th>
                   <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
               </tr>
               </thead>
               <tbody>
                @forelse ($schools as $school)                    
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="school-{{ $school->id }}">
                        <td class="px-6 py-2">
                            <button 
                                @click="expandedRows[{{ $school->id }}] = !expandedRows[{{ $school->id }}]"
                                class="inline-flex items-center justify-center w-6 h-6 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                :class="{ 'bg-blue-100 dark:bg-blue-900': expandedRows[{{ $school->id }}] }"
                                title="عرض التفاصيل"
                            >
                                <svg :class="{ 'rotate-180': expandedRows[{{ $school->id }}] }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                            </button>
                        </td>
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $schools->firstItem() + $loop->index  }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->name }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ config('schools.genders')[$school->gender] ?? $school->gender }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $school->stage }}</td>
                        <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                            <flux:badge color="{{ $school->is_complex ? 'blue' : 'gray' }}">{{ $school->is_complex ? 'ضمن مجمع' : 'مستقلة' }}</flux:badge>
                        </td>
                        <td class="px-6 py-2 text-gray-700">
                            @php
                                $statusLabel = config('schools.statuses')[$school->status] ?? $school->status;
                                $statusColor = $school->status === 'نشط' ? 'green' : ($school->status === 'غير نشط' ? 'red' : 'yellow');
                            @endphp
                            <flux:badge color="{{ $statusColor }}">{{ $statusLabel }}</flux:badge>
                        </td>
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

                    {{-- Collapsible Details Row --}}
                    <tr x-show="expandedRows[{{ $school->id }}]" 
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:leave="transition ease-in duration-200"
                        class="bg-blue-50 dark:bg-blue-900/30 border-b border-gray-200 dark:border-gray-600">
                        <td colspan="11" class="px-6 py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">القطاع التعليمي</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $school->sector->name ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">نوع التعليم / نوع المبنى</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $school->school_type ?? 'غير محدد' }} / {{ $school->building_type ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">الرقم الوزاري</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $school->ministry_code ?? 'غير محدد' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">مدير المدرسة</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                        <flux:heading class="flex items-center gap-2">
                                            {{ $school->principal->name ?? 'غير محدد' }}
                                            <flux:tooltip toggleable>
                                                <flux:button icon="information-circle" size="sm" variant="ghost" />
                                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                    <p>email: {{ $school->principal->email ?? 'غير محدد' }}</p>
                                                    <p>phone: {{ $school->principal->phone ?? 'غير محدد' }}</p>
                                                    <p>national ID: {{ $school->principal->nastional_id ?? 'غير محدد' }}</p>
                                                    <p>created at: {{ $school->principal?->created_at?->format('Y-m-d') ?: 'غير محدد' }}</p>
                                                </flux:tooltip.content>
                                            </flux:tooltip>
                                        </flux:heading>                                        
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">منسق الموهوبين</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">
                                        <flux:heading class="flex items-center gap-2">
                                            {{ $school->coordinator->name ?? 'غير محدد' }}
                                            <flux:tooltip toggleable>
                                                <flux:button icon="information-circle" size="sm" variant="ghost" />
                                                <flux:tooltip.content class="max-w-[20rem] space-y-2">
                                                    <p>email: {{ $school->coordinator->email ?? 'غير محدد' }}</p>
                                                    <p>phone: {{ $school->coordinator->phone ?? 'غير محدد' }}</p>
                                                    <p>national ID: {{ $school->coordinator->nastional_id ?? 'غير محدد' }}</p>
                                                    <p>created at: {{ $school->coordinator?->created_at?->format('Y-m-d') ?: 'غير محدد' }}</p>
                                                </flux:tooltip.content>
                                            </flux:tooltip>
                                        </flux:heading>                                        
                                    </p>                                    
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase">تاريخ التحديث</p>
                                    <p class="text-sm text-gray-900 dark:text-gray-100 mt-1">{{ $school->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                            </div>
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
