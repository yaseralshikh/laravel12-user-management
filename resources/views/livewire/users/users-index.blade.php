<div>
   <div class="p-3">
        {{-- for show Create modal --}}
        <livewire:users.user-create />

        {{-- for show Edit modal --}}
        <livewire:users.user-edit />

        {{-- for show Delete modal --}}
        <flux:modal name="delete-user" class="min-w-[22rem]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">Delete user?</flux:heading>
                    <flux:text class="mt-2">
                        <p>You're about to delete this user.</p>
                        <p>This action cannot be reversed.</p>
                    </flux:text>
                </div>
                <div class="flex gap-2">
                    <flux:spacer />
                    <flux:modal.close>
                        <flux:button variant="ghost">Cancel</flux:button>
                    </flux:modal.close>
                    <flux:button type="submit" variant="danger" wire:click="destroy()">Delete user</flux:button>
                </div>
            </div>
        </flux:modal>

        {{-- for Create & Search button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
            <div class="flex flex-row justify-start items-center gap-4 mb-4">
                {{-- زر إنشاء منشور --}}
                <flux:modal.trigger name="create-user">
                    @permission('users-create')
                        <flux:button variant="primary" class="flex items-center gap-2">
                            <flux:icon.plus class="w-4 h-4" />
                            Create user
                        </flux:button>
                    @else
                        <flux:button variant="subtle" class="flex items-center gap-2" disabled>
                            <flux:icon.plus class="w-4 h-4" />
                            Create user
                        </flux:button>
                    @endpermission
                </flux:modal.trigger>

                <div class="flex items-center gap-2">
                    {{-- زر تصدير Excel --}}
                    <x-button wire:click="exportExcel" color="success" class="p-2 w-10 h-10 flex items-center justify-center" title="تصدير Excel">
                        <flux:icon.arrow-down-on-square variant="solid" class="w-5 h-5 text-green-600" />
                    </x-button>

                    {{-- زر تصدير PDF --}}
                    <x-button wire:click="exportPdf" color="success" class="p-2 w-10 h-10 flex items-center justify-center" title="تصدير PDF">
                        <flux:icon.document-text variant="solid" class="w-5 h-5 text-red-600" />
                    </x-button>

                    {{-- total users --}}
                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Total Users: ({{ $users->total() }})</span>
                </div>
            </div>

            {{-- loading search --}}
            <div wire:loading.delay wire:target="term" dir="rtl" class="text-sm text-gray-500 dark:text-gray-400 mt-1">جاري البحث...</div>

            {{-- نموذج البحث --}}
            <div class="w-full md:w-96 relative">

                <flux:input placeholder="Search users" wire:model.live.debounce.300ms="term">
                    <x-slot name="iconTrailing">
                        @if($term)
                            <flux:button size="sm" variant="subtle" icon="x-mark" class="-mr-1" wire:click="$set('term', '')" />
                        @endif
                    </x-slot>
                </flux:input>
            </div>
        </div>

        {{-- جدول عرض المنشورات --}}
       <div class="overflow-x-auto mt-4 rounded-lg shadow">
           <table class="w-full text-sm text-left text-gray-700">
               <thead class="text-xs uppercase bg-gray-500/20 text-gray-700 text-center">
               <tr>
                   <th scope="col" class="px-6 py-3">Index</th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('name')">
                        Name
                        @if($sortField === 'name')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('email')">
                        Email
                        @if($sortField === 'email')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('phone')">
                        Phone
                        @if($sortField === 'phone')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="sortBy('created_at')">
                        Created at
                        @if($sortField === 'created_at')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                   <th scope="col" class="px-6 py-3 w-70">Role</th>
                   <th scope="col" class="px-6 py-3 w-70">Actions</th>
               </tr>
               </thead>
               <tbody>
                @forelse ($users as $user)                    
                    <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200 text-center" wire:key="user-{{ $user->id }}">
                        <td class="px-6 py-2 font-medium text-gray-900">{{ $users->firstItem() + $loop->index  }}</td>
                        <td class="px-6 py-2 text-gray-700">{{ $user->name }}</td>
                        <td class="px-6 py-2 text-gray-700">{{ $user->email }}</td>
                        <td class="px-6 py-2 text-gray-700">{{ $user->created_at->format('Y-m-d') }}</td>
                        <td class="px-6 py-2 text-gray-700">
                            <flux:badge 
                                color="{{ $user->roles[0]->name == 'user' ? 'cyan' : 'green' }}" icon="user">
                                {{ $user->roles[0]->name }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-2 space-x-1">
                            @role('admin|superadmin')
                                {{-- Check if the user is not a superadmin before allowing deletion --}}
                                @if ($user->roles[0]->name != 'superadmin') <!-- Assuming role 1 is superadmin and should not be edit -->
                                    @permission('users-update')
                                        <flux:button variant="primary" size="sm" wire:click="edit({{ $user->id }})">Edit</flux:button>
                                    @else
                                        <flux:button variant="subtle" size="sm" disabled>Edit</flux:button>
                                    @endpermission
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>Edit</flux:button>
                                @endif
                            @else
                                <flux:button variant="subtle" size="sm" disabled>Edit</flux:button>
                            @endrole

                            @role('admin|superadmin')
                                {{-- Check if the user is not a superadmin before allowing deletion --}}
                                @if ($user->roles[0]->name != 'superadmin') <!-- Assuming role 1 is superadmin and should not be delete -->
                                    @permission('users-delete')
                                        <flux:button variant="danger" size="sm" wire:click="delete({{ $user->id }})">Delete</flux:button>
                                    @else
                                        <flux:button variant="subtle" size="sm" disabled>Delete</flux:button>
                                    @endpermission
                                @else
                                    <flux:button variant="subtle" size="sm" disabled>Delete</flux:button>
                                @endif
                            @else
                                <flux:button variant="subtle" size="sm" disabled>Delete</flux:button>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-700 dark:text-gray-300 py-4">No matching data</td>
                    </tr>
                @endforelse
               </tbody>
           </table>
            <div class="m-4">
                {{ $users->links() }}
            </div>
       </div>
   </div> 
</div>

@push('scripts')
<script>
    //console.log('Script خاص بهذه الصفحة فقط');
</script>
@endpush