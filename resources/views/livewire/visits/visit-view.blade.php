<flux:modal name="view-visit" class="w-[95vw] [:where(&)]:max-w-6xl">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">تفاصيل الزيارة</flux:heading>
        </div>

        @if($visit)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Basic Information --}}
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">المدرسة</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $visit->school->name }}</p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">المستخدم</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $visit->user->name }}</p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">نوع الزيارة</span>
                        <p class="mt-1">
                            <span class="px-2 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded text-sm">
                                {{ $visit->visit_type }}
                            </span>
                        </p>
                    </div>

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">تاريخ الزيارة</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($visit->visit_date)->format('Y-m-d H:i') }}
                        </p>
                    </div>
                </div>

                {{-- Additional Information --}}
                <div class="space-y-4">
                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">السنة الدراسية</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $visit->academicYear->name }}</p>
                    </div>

                    @if($visit->programCycle)
                        <div>
                            <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">برنامج الدورة</span>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">
                                {{ $visit->programCycle->program->name }} - {{ $visit->programCycle->term }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">تم الإضافة في</span>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($visit->created_at)->format('Y-m-d H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Objective --}}
            @if($visit->objective)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">الهدف</span>
                    <p class="mt-2 text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $visit->objective }}</p>
                </div>
            @endif

            {{-- Notes --}}
            @if($visit->notes)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">الملاحظات</span>
                    <p class="mt-2 text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $visit->notes }}</p>
                </div>
            @endif

            {{-- Recommendations --}}
            @if($visit->recommendations)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">التوصيات</span>
                    <p class="mt-2 text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $visit->recommendations }}</p>
                </div>
            @endif

            {{-- Attachments --}}
            @if($visit->attachments->count() > 0)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 block mb-3">المرفقات ({{ $visit->attachments->count() }})</span>
                    <div class="space-y-2">
                        @foreach($visit->attachments as $attachment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-800 rounded">
                                <div class="flex items-center gap-2">
                                    <flux:icon.document class="w-5 h-5 text-gray-500" />
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $attachment->original_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ number_format($attachment->file_size / 1024, 2) }} KB
                                        </p>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-sky-600 hover:text-sky-700">
                                    <flux:icon.arrow-down-tray class="w-5 h-5" />
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <div class="flex gap-2 border-t border-gray-200 dark:border-gray-700 pt-6">
            @if($visit)
                <flux:button wire:click="downloadPdf" variant="filled" class="bg-green-600 hover:bg-green-700">
                    <flux:icon.arrow-down-tray class="w-5 h-5" />
                    تنزيل تقرير PDF
                </flux:button>
            @endif
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="primary">إغلاق</flux:button>
            </flux:modal.close>
        </div>
    </div>
</flux:modal>
