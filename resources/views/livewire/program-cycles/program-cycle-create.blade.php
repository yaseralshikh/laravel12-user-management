<div>
    <flux:modal name="create-program-cycle" class="md:w-full md:max-w-lg">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">إنشاء دورة برنامج</flux:heading>
                <flux:text class="mt-2">أضف دورة برنامج جديدة.</flux:text>
            </div>

            <flux:select wire:model="program_id" label="البرنامج" placeholder="اختر البرنامج">
                @foreach($programs as $program)
                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                @endforeach
            </flux:select>

            <flux:select wire:model="academic_year_id" label="السنة الأكاديمية" placeholder="اختر السنة الأكاديمية">
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                @endforeach
            </flux:select>

            <flux:input type="number" wire:model="term" label="الفصل (اختياري)" placeholder="1 أو 2" min="1" max="2" />

            <flux:select wire:model="status" label="الحالة">
                <option value="in_progress">جاري</option>
                <option value="completed">مكتمل</option>
                <option value="suspended">معلق</option>
                <option value="canceled">ملغى</option>
            </flux:select>

            <flux:input type="date" wire:model="start_date" label="تاريخ البداية (اختياري)" />
            <flux:input type="date" wire:model="end_date" label="تاريخ النهاية (اختياري)" />
            <flux:textarea wire:model="notes" label="الملاحظات (اختياري)" placeholder="أضف ملاحظاتك..." />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="submit">حفظ</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
