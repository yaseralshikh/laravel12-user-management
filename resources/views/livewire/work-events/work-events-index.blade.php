<div>
    <div class="p-3">
        <flux:heading
            size="xl"
            class="mb-6 text-center font-bold text-sky-600 dark:text-gray-200 tracking-wide">
            @lang('messages.work_events_management', ['management' => 'إدارة '])
        </flux:heading>
        <div class="mx-auto mb-6 h-1 w-full rounded bg-sky-500 dark:bg-gray-500"></div>

        {{-- for show Create modal --}}
        <livewire:work-events.work-event-create />

        {{-- for show Edit modal --}}
        <livewire:work-events.work-event-edit />

        {{-- for show View modal --}}
        <livewire:work-events.work-event-view />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-work-event" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف الحدث؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف هذا الحدث.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف الحدث</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إضافة حدث --}}
                <flux:modal.trigger name="create-work-event">
                    @permission('work_events-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة حدث
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إضافة حدث
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

                    {{-- total events --}}
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي الأحداث: ({{ $workEvents->total() }})</span>
                </div>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث في الأحداث" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- فلاتر التصنيف --}}
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
            {{-- Event Type Filter --}}
            <flux:select wire:model.live="eventTypeFilter" label="نوع الحدث">
                <option value="">جميع الأنواع</option>
                @foreach($eventTypes as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </flux:select>

            {{-- User Filter --}}
            <flux:select wire:model.live="userFilter" label="المستخدم">
                <option value="">جميع المستخدمين</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </flux:select>

            {{-- Academic Year Filter --}}
            <flux:select wire:model.live="academicYearFilter" label="السنة الدراسية">
                <option value="">جميع السنوات</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach
            </flux:select>

            {{-- Program Cycle Filter --}}
            <flux:select wire:model.live="programCycleFilter" label="برنامج الدورة">
                <option value="">جميع البرامج</option>
                @foreach($programCycles as $cycle)
                    <option value="{{ $cycle->id }}">{{ $cycle->program->name }} - {{ $cycle->term }}</option>
                @endforeach
            </flux:select>

            {{-- Date From Filter --}}
            <flux:input wire:model.live="dateFromFilter" type="date" label="من التاريخ" />

            {{-- Date To Filter --}}
            <flux:input wire:model.live="dateToFilter" type="date" label="إلى التاريخ" />
        </div>

        <style>
            [x-cloak] { display:none !important; }
        </style>

        {{-- جدول عرض الأحداث --}}
        <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase bg-sky-500/10 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                    <tr>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('event_date')">
                            التاريخ
                            @if($sortField === 'event_date')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('title')">
                            العنوان
                            @if($sortField === 'title')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                            @endif
                        </th>
                        <th scope="col" class="px-6 py-3">نوع الحدث</th>
                        <th scope="col" class="px-6 py-3">المستخدم</th>
                        <th scope="col" class="px-6 py-3">المكان</th>
                        <th scope="col" class="px-6 py-3">الوقت</th>
                        <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($workEvents as $workEvent)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="event-{{ $workEvent->id }}">
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($workEvent->event_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ Str::limit($workEvent->title, 30) }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                                <flux:badge color="purple">{{ $workEvent->event_type }}</flux:badge>
                            </td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $workEvent->user->name }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $workEvent->location ?? '-' }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">
                                @if($workEvent->starts_at && $workEvent->ends_at)
                                    {{ $workEvent->starts_at }} - {{ $workEvent->ends_at }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-2 space-x-1">
                                @permission('work_events-view')
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="eye"
                                        wire:click="view({{ $workEvent->id }})"
                                        title="عرض" />
                                @endpermission

                                @permission('work_events-edit')
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        wire:click="edit({{ $workEvent->id }})"
                                        title="تعديل" />
                                @endpermission

                                @permission('work_events-delete')
                                    <flux:button
                                        size="sm"
                                        variant="ghost"
                                        icon="trash"
                                        wire:click="delete({{ $workEvent->id }})"
                                        title="حذف" />
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                لا توجد أحداث
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $workEvents->links() }}
        </div>
    </div>
</div>
