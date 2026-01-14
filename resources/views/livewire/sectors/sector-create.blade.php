<div>
    <flux:modal name="create-sector" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">إنشاء قطاع</flux:heading>
                <flux:text class="mt-2">أضف تفاصيل القطاع الجديد.</flux:text>
            </div>

            <flux:input wire:model="name" label="اسم القطاع" placeholder="أدخل اسم القطاع" />
            <flux:textarea wire:model="description" label="الوصف" placeholder="أدخل وصف القطاع (اختياري)" />
            <flux:select wire:model="status" label="الحالة">
                <option value="active">نشط</option>
                <option value="inactive">غير نشط</option>
            </flux:select>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="submit">حفظ</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
