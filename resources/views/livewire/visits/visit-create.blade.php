<flux:modal name="create-visit" class="min-w-[28rem]">
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">إضافة زيارة جديدة</flux:heading>
        </div>

        <div class="space-y-4">
            {{-- School --}}
            <div>
                <flux:label for="school_id">المدرسة <span class="text-red-500">*</span></flux:label>
                <flux:select
                    id="school_id"
                    wire:model="school_id"
                    placeholder="اختر المدرسة">
                    <option value="">-- اختر المدرسة --</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </flux:select>
                @error('school_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- User --}}
            <div>
                <flux:label for="user_id">المستخدم <span class="text-red-500">*</span></flux:label>
                <flux:select
                    id="user_id"
                    wire:model="user_id"
                    placeholder="اختر المستخدم">
                    <option value="">-- اختر المستخدم --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </flux:select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Academic Year --}}
            <div>
                <flux:label for="academic_year_id">السنة الدراسية <span class="text-red-500">*</span></flux:label>
                <flux:select
                    id="academic_year_id"
                    wire:model="academic_year_id"
                    placeholder="اختر السنة الدراسية">
                    <option value="">-- اختر السنة الدراسية --</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}">{{ $year->name }}</option>
                    @endforeach
                </flux:select>
                @error('academic_year_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Program Cycle --}}
            <div>
                <flux:label for="program_cycle_id">برنامج الدورة</flux:label>
                <flux:select
                    id="program_cycle_id"
                    wire:model="program_cycle_id"
                    placeholder="اختر برنامج الدورة (اختياري)">
                    <option value="">-- اختر برنامج الدورة --</option>
                    @foreach($programCycles as $cycle)
                        <option value="{{ $cycle->id }}">{{ $cycle->program->name }} - {{ $cycle->term }}</option>
                    @endforeach
                </flux:select>
                @error('program_cycle_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Visit Date --}}
            <div>
                <flux:label for="visit_date">تاريخ الزيارة <span class="text-red-500">*</span></flux:label>
                <flux:input
                    id="visit_date"
                    type="date"
                    wire:model="visit_date" />
                @error('visit_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Visit Type --}}
            <div>
                <flux:label for="visit_type">نوع الزيارة <span class="text-red-500">*</span></flux:label>
                <flux:select
                    id="visit_type"
                    wire:model="visit_type"
                    placeholder="اختر نوع الزيارة">
                    <option value="">-- اختر نوع الزيارة --</option>
                    @foreach($visitTypes as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>
                @error('visit_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Objective --}}
            <div>
                <flux:label for="objective">الهدف</flux:label>
                <flux:textarea
                    id="objective"
                    wire:model="objective"
                    placeholder="أدخل الهدف من الزيارة" />
                @error('objective') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Notes --}}
            <div>
                <flux:label for="notes">الملاحظات</flux:label>
                <flux:textarea
                    id="notes"
                    wire:model="notes"
                    placeholder="أدخل الملاحظات" />
                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Recommendations --}}
            <div>
                <flux:label for="recommendations">التوصيات</flux:label>
                <flux:textarea
                    id="recommendations"
                    wire:model="recommendations"
                    placeholder="أدخل التوصيات" />
                @error('recommendations') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex gap-2">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost">إلغاء</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary" wire:click="save">إضافة الزيارة</flux:button>
        </div>
    </div>
</flux:modal>
