<div>
    <flux:modal name="create-user" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Create User</flux:heading>
                <flux:text class="mt-2">Add details for user.</flux:text>
            </div>

            <flux:input wire:model="name" label="Name" placeholder="User name" />
            <flux:input wire:model="nastional_id" label="National ID" placeholder="User national ID" />
            <flux:input wire:model="email" label="Email" placeholder="User email" />
            <flux:input wire:model="phone" label="Phone" placeholder="User phone" />
            <flux:select wire:model="sector_id" label="Educational Sector" placeholder="User educational sector">
                <option value="">Select educational sector (اختياري)</option>
                @foreach($sectors as $sector)
                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                @endforeach
            </flux:select>
            <flux:input wire:model="password" type="password" label="Password"  placeholder="User password" />
            <flux:input wire:model="password_confirmation" type="password" label="Confirm Password" placeholder="Confirm password" />
            <flux:select wire:model="role" label="Role">
                <option value="">Select role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                @endforeach
            </flux:select>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="submit">Save</flux:button>
            </div>
        </div>
    </flux:modal>
</div>