<div>
    <flux:modal name="edit-school" class="md:w-[600px]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">تعديل المدرسة</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل المدرسة</flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="اسم المدرسة" placeholder="اسم المدرسة" />
                <flux:input wire:model="ministry_code" label="الرمز الوزاري" placeholder="الرمز الوزاري" />
                
                <flux:select wire:model="gender" label="نوع المدرسة">
                    <option value="">اختر النوع</option>
                    @foreach($genders as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="school_type" label="نوع التعليم">
                    <option value="">اختر نوع التعليم</option>
                    @foreach($schoolTypes as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="building_type" label="نوع المبنى">
                    <option value="">اختر نوع المبنى</option>
                    @foreach($buildingTypes as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="status" label="حالة المدرسة">
                    <option value="">اختر الحالة</option>
                    @foreach($statuses as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="educational_sector" label="القطاع التعليمي">
                    <option value="">اختر القطاع التعليمي</option>
                    @foreach($educationalSectors as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:select wire:model="stage" label="المراحل الدراسية" >
                    <option value="" disabled>اختر المراحل</option>
                    @foreach($stageOptions as $key => $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </flux:select>    

                
                <flux:select wire:model="coordinator_id" label="المنسق">
                    <option value="">اختر المنسق (اختياري)</option>
                    @foreach($coordinators as $coordinator)
                    <option value="{{ $coordinator->id }}">{{ $coordinator->name }}</option>
                    @endforeach
                </flux:select>
                
                <flux:select wire:model="principal_id" label="المدير">
                    <option value="">اختر المدير (اختياري)</option>
                    @foreach($principals as $principal)
                    <option value="{{ $principal->id }}">{{ $principal->name }}</option>
                    @endforeach
                </flux:select>

                <flux:checkbox wire:model="is_complex" label="مدرسة مجمع" class="col-span-2" />
            </div>

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" wire:click="updateSchool">تحديث</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
