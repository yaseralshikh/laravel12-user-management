<div>
    <div class="p-3">
        {{-- for show Create modal --}}
        <livewire:sectors.sector-create />

        {{-- for show Edit modal --}}
        <livewire:sectors.sector-edit />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-sector" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">حذف القطاع؟</flux:heading>
                    <flux:text class="mt-2">
                        <p>أنت على وشك حذف هذا القطاع.</p>
                        <p>هذا الإجراء لا يمكن التراجع عنه.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">إلغاء</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">حذف القطاع</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إنشاء قطاع --}}
                <flux:modal.trigger name="create-sector">
                    @permission('sectors-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء قطاع
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            إنشاء قطاع
                        </flux:button>
                    @endpermission
                </flux:modal.trigger>

                {{-- total sectors --}}
                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">إجمالي القطاعات: ({{ $sectors->total() }})</span>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">
                <flux:input placeholder="البحث عن قطاع" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- جدول عرض القطاعات --}}
        <div class="overflow-x-auto mt-4 rounded-lg shadow dark:shadow-gray-800">
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300">
                <thead class="text-xs uppercase bg-gray-500/20 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300 text-center">
                    <th scope="col" class="px-6 py-3">رقم</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                        الاسم
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('status')">
                        الحالة
                        @if($sortField === 'status')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        تاريخ الإنشاء
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 w-70">الإجراءات</th>
                </thead>
                <tbody>
                    @forelse ($sectors as $sector)
                        <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 text-center" wire:key="sector-{{ $sector->id }}">
                            <td class="px-6 py-2 font-medium text-gray-900 dark:text-gray-100">{{ $sectors->firstItem() + $loop->index }}</td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $sector->name }}</td>
                            <td class="px-6 py-2 text-gray-700">
                                <flux:badge 
                                    color="{{ $sector->status == 'active' ? 'green' : 'red' }}">
                                    {{ $sector->status == 'active' ? 'نشط' : 'غير نشط' }}
                                </flux:badge>
                            </td>
                            <td class="px-6 py-2 text-gray-700 dark:text-gray-300">{{ $sector->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-2 space-x-1">
                                @permission('sectors-update')
                                    <flux:button variant="primary" size="sm" wire:click="edit({{ $sector->id }})">تعديل</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>تعديل</flux:button>
                                @endpermission

                                @permission('sectors-delete')
                                    <flux:button variant="danger" size="sm" wire:click="delete({{ $sector->id }})">حذف</flux:button>
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>حذف</flux:button>
                                @endpermission
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-700 dark:text-gray-300 py-4">لا توجد بيانات</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="m-4">
                {{ $sectors->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    //console.log('Script خاص بهذه الصفحة فقط');
</script>
@endpush
