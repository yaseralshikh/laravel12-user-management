<div>
    <flux:modal name="edit-user" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edit User</flux:heading>
                <flux:text class="mt-2">Edit details for user.</flux:text>
            </div>

            <flux:input wire:model="name" label="Name" placeholder="Pser name" />
            <flux:input wire:model="email" label="Email" placeholder="Pser email" />
            <flux:input wire:model="password" type="password" label="Password"  placeholder="Pser password" />
            <flux:input wire:model="password_confirmation" type="password" label="Confirm Password" placeholder="Confirm password" />
            @if ($this->role != 1) <!-- Assuming role 1 is superadmin and should not be editable roles -->
                <flux:select wire:model="role" label="Role">
                    <option value="">Select role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </flux:select>
            @endif
            
            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="updateUser">Update</flux:button>
            </div>
        </div>
    </flux:modal>
</div>