<div>
    <flux:modal name="edit-academic-year" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">تعديل السنة الأكاديمية</flux:heading>
                <flux:text class="mt-2">عدّل تفاصيل السنة الأكاديمية.</flux:text>
            </div>

            <flux:input wire:model="name" label="اسم السنة الأكاديمية" placeholder="مثال: 2025-2026" />
            <flux:input wire:model="starts_on" type="date" label="تاريخ البداية" />
            <flux:input wire:model="ends_on" type="date" label="تاريخ النهاية" />
            <flux:select wire:model="status" label="الحالة">
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </flux:select>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="updateAcademicYear">تحديث</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
