<flux:modal name="view-work-event" class="max-w-2xl">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">تفاصيل الحدث</flux:heading>
        </div>

        @if($workEvent)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Basic Information --}}
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">العنوان</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $workEvent['title'] }}</p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">نوع الحدث</span>
                        <p class="mt-1">
                            <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded text-sm">
                                {{ $workEvent['event_type'] }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">تاريخ الحدث</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($workEvent['event_date'])->format('Y-m-d') }}
                        </p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">المستخدم</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $workEvent['user']['name'] }}</p>
                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">المكان</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $workEvent['location'] ?? '-' }}</p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">السنة الدراسية</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $workEvent['academic_year']['name'] }}</p>
                    </div>

                    @if($workEvent['program_cycle'])
                        <div>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">برنامج الدورة</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">
                                {{ $workEvent['program_cycle']['program']['name'] }} - {{ $workEvent['program_cycle']['term'] }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">الوقت</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            @if($workEvent['starts_at'] && $workEvent['ends_at'])
                                {{ $workEvent['starts_at'] }} - {{ $workEvent['ends_at'] }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- Description --}}
            @if($workEvent['description'])
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">الوصف</span>
                    <p class="mt-2 text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $workEvent['description'] }}</p>
                </div>
            @endif
        @endif

        <div class="flex gap-2 border-t border-gray-200 dark:border-gray-700 pt-6">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="primary">إغلاق</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
