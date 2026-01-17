<flux:modal name="create-work-event" class="max-w-2xl">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">إضافة حدث جديد</flux:heading>
        </div>

        <form wire:submit="save" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div>
                    <flux:input wire:model="title" label="العنوان *" placeholder="أدخل عنوان الحدث" />
                    @error('title')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Event Type --}}
                <div>
                    <flux:select wire:model="event_type" label="نوع الحدث *" placeholder="اختر نوع الحدث">
                        <option value="">اختر...</option>
                        @foreach($eventTypes as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </flux:select>
                    @error('event_type')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Event Date --}}
                <div>
                    <flux:input wire:model="event_date" type="date" label="تاريخ الحدث *" />
                    @error('event_date')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- User --}}
                <div>
                    <flux:select wire:model="user_id" label="المستخدم *" placeholder="اختر المستخدم">
                        <option value="">اختر...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('user_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Academic Year --}}
                <div>
                    <flux:select wire:model="academic_year_id" label="السنة الدراسية *" placeholder="اختر السنة">
                        <option value="">اختر...</option>
                        @foreach($academicYears as $year)
                            <option value="{{ $year->id }}">{{ $year->name }}</option>
                        @endforeach
                    </flux:select>
                    @error('academic_year_id')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Program Cycle --}}
                <div>
                    <flux:select wire:model="program_cycle_id" label="برنامج الدورة" placeholder="اختر برنامج الدورة (اختياري)">
                        <option value="">بدون برنامج</option>
                        @foreach($programCycles as $cycle)
                            <option value="{{ $cycle->id }}">{{ $cycle->program->name }} - {{ $cycle->term }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Location --}}
                <flux:input wire:model="location" label="المكان" placeholder="أدخل مكان الحدث (اختياري)" />

                {{-- Start Time --}}
                <flux:input wire:model="starts_at" type="time" label="وقت البدء" placeholder="HH:MM" />
            </div>

            {{-- End Time --}}
            <flux:input wire:model="ends_at" type="time" label="وقت الانتهاء" placeholder="HH:MM" />

            {{-- Description --}}
            <flux:textarea wire:model="description" label="الوصف" placeholder="أدخل وصف الحدث (اختياري)" />

            <div class="flex gap-2 border-t border-gray-200 dark:border-gray-700 pt-6">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">إلغاء</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="primary">إضافة الحدث</flux:button>
            </div>
        </form>
    </div>
</flux:modal>
