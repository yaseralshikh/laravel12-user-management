<div>
    <div class="p-3">
        <flux:heading
            size="xl"
            class="mb-6 text-center font-bold text-sky-600 dark:text-gray-200 tracking-wide">
            @lang('messages.visits_management', ['management' => 'إدارة '])
        </flux:heading>
        <div class="mx-auto mb-6 h-1 w-full rounded bg-sky-500 dark:bg-gray-500"></div>

        {{-- for show Create modal --}}
        <livewire:visits.visit-create />

        {{-- for show Edit modal --}}
        <livewire:visits.visit-edit />

        {{-- for show View modal --}}
        <livewire:visits.visit-view />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-visit" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف زيارة؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف هذه الزيارة.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف الزيارة</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إضافة زيارة --}}
                <flux:modal.trigger name="create-visit">
                    @permission('visits-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة زيارة
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة زيارة
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

                    {{-- total visits --}}
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي الزيارات: ({{ $visits->total() }})</span>
                </div>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث في الزيارات" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- فلاتر التصنيف - الصف الأول --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">
            {{-- نوع الزيارة --}}
            <flux:select
                wire:model.live="visitTypeFilter"
                label="نوع الزيارة">
                <option value="">جميع الأنواع</option>
                @foreach($visitTypes as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            {{-- المدرسة --}}
            <flux:select
                wire:model.live="schoolFilter"
                label="المدرسة">
                <option value="">جميع المدارس</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}">{{ $school->name }}</option>
                @endforeach
            </flux:select>

            {{-- المستخدم --}}
            <flux:select
                wire:model.live="userFilter"
                label="المستخدم">
                <option value="">جميع المستخدمين</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </flux:select>
        </div>

        {{-- فلاتر التصنيف - الصف الثاني --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            {{-- السنة الدراسية --}}
            <flux:select
                wire:model.live="academicYearFilter"
                label="السنة الدراسية">
                <option value="">جميع السنوات</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach
            </flux:select>

            {{-- برنامج الدورة --}}
            <flux:select
                wire:model.live="programCycleFilter"
                label="برنامج الدورة">
                <option value="">جميع البرامج</option>
                @foreach($programCycles as $cycle)
                    <option value="{{ $cycle->id }}">{{ $cycle->program->name }} - {{ $cycle->term }}</option>
                @endforeach
            </flux:select>

            {{-- من التاريخ إلى التاريخ --}}
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                    <flux:input
                        type="date"
                        wire:model.live="dateFromFilter"
                        label="من" />
                </div>
                <div class="flex-1">
                    <flux:input
                        type="date"
                        wire:model.live="dateToFilter"
                        label="إلى" />
                </div>
            </div>

        <style>
            [x-cloak] { display:none !important; }
        </style>
    </div>
    {{-- جدول عرض الزيارات --}}
    <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead class="text-base uppercase bg-sky-500/10 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 font-bold">
                <tr>
                    <th scope="col" class="px-8 py-5 cursor-pointer text-center whitespace-nowrap" wire:click="sortBy('visit_date')">
                        التاريخ
                        @if($sortField === 'visit_date')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">المدرسة</th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">المستخدم</th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">نوع الزيارة</th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">الهدف</th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">السنة الدراسية</th>
                    <th scope="col" class="px-8 py-5 text-center whitespace-nowrap">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($visits as $visit)
                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b-2 border-gray-200 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-600/50 transition" wire:key="visit-{{ $visit->id }}">
                        <td class="px-8 py-4 text-center text-gray-700 dark:text-gray-300 whitespace-nowrap font-semibold">{{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d') }}</td>
                        <td class="px-8 py-4 text-center">
                            <flux:badge color="blue" class="text-base px-3 py-2">{{ $visit->school->name }}</flux:badge>
                        </td>
                        <td class="px-8 py-4 text-center text-gray-700 dark:text-gray-300 font-medium">{{ $visit->user->name }}</td>
                        <td class="px-8 py-4 text-center">
                            <flux:badge color="purple" class="text-base px-3 py-2">{{ $visit->visit_type }}</flux:badge>
                        </td>
                        <td class="px-8 py-4 text-center text-gray-700 dark:text-gray-300">
                            <span title="{{ $visit->objective }}">{{ Str::limit($visit->objective, 30) }}</span>
                        </td>
                        <td class="px-8 py-4 text-center text-gray-700 dark:text-gray-300 font-medium">{{ $visit->academicYear->name }}</td>
                        <td class="px-8 py-4">
                            <div class="flex gap-3 items-center justify-center">
                                <flux:button
                                    variant="ghost"
                                    icon="eye"
                                    wire:click="view({{ $visit->id }})"
                                    title="عرض الزيارة"
                                    class="text-xl" />
                                
                                <flux:button
                                    icon="pencil-square"
                                    wire:click="edit({{ $visit->id }})"
                                    title="تحديث الزيارة"
                                    class="text-xl" />
                                
                                <flux:button
                                    variant="ghost"
                                    icon="trash"
                                    wire:click="delete({{ $visit->id }})"
                                    title="حذف الزيارة"
                                    class="text-xl" />
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-8 py-12 text-center text-gray-500 dark:text-gray-400 text-lg">
                            لا توجد زيارات
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 text-base">
        {{ $visits->links() }}
    </div>
</div>
