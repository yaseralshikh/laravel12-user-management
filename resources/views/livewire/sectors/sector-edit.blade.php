<div>
    <flux:modal name="edit-sector" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">تعديل القطاع</flux:heading>
                <flux:text class="mt-2">عدّل تفاصيل القطاع.</flux:text>
            </div>

            <flux:input wire:model="name" label="اسم القطاع" placeholder="أدخل اسم القطاع" />
            <flux:textarea wire:model="description" label="الوصف" placeholder="أدخل وصف القطاع (اختياري)" />
            <flux:select wire:model="status" label="الحالة">
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </flux:select>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="updateSector">تحديث</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
