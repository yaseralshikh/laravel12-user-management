<div>
    <flux:modal name="create-program" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">إنشاء برنامج</flux:heading>
                <flux:text class="mt-2">أضف تفاصيل البرنامج الجديد.</flux:text>
            </div>

            <flux:input wire:model="name" label="اسم البرنامج" placeholder="أدخل اسم البرنامج" />
            <flux:textarea wire:model="description" label="الوصف" placeholder="أدخل وصف البرنامج (اختياري)" />
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
