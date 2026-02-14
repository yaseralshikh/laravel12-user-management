<flux:modal name="edit-visit" class="md:w-96">
    <flux:heading level="2">{{ __('Edit Visit') }}</flux:heading>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Sector Select -->
        <flux:select wire:model.live="sector_id" label="{{ __('Sector') }}" placeholder="{{ __('Select Sector') }}">
            <option value="">{{ __('Select Sector') }}</option>
            @foreach($sectors as $sector)
                <option value="{{ $sector->id }}">{{ $sector->name }}</option>
            @endforeach
        </flux:select>

        <!-- Stage Select -->
        <flux:select wire:model.live="stage" label="{{ __('Academic Stage') }}" placeholder="{{ __('Select Stage') }}">
            <option value="">{{ __('Select Stage') }}</option>
            @foreach($stages as $stageValue)
                <option value="{{ $stageValue }}">{{ $stageValue }}</option>
            @endforeach
        </flux:select>

        <!-- School Select -->
        <flux:select wire:model.live="school_id" label="{{ __('School') }}" placeholder="{{ __('Select School') }}" :disabled="!$sector_id || !$stage">
            <option value="">{{ __('Select School') }}</option>
            @foreach($schools as $school)
                <option value="{{ $school->id }}">{{ $school->name }}</option>
            @endforeach
        </flux:select>

        <!-- User Select -->
        <flux:select wire:model="user_id" label="{{ __('User') }}" placeholder="{{ __('Select User') }}">
            <option value="">{{ __('Select User') }}</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </flux:select>

        <!-- Academic Year Select -->
        <flux:select wire:model="academic_year_id" label="{{ __('Academic Year') }}" placeholder="{{ __('Select Year') }}">
            <option value="">{{ __('Select Year') }}</option>
            @foreach($academicYears as $year)
                <option value="{{ $year->id }}">{{ $year->name }}</option>
            @endforeach
        </flux:select>

        <!-- Visit Date Input -->
        <flux:input wire:model="visit_date" type="date" label="{{ __('Visit Date') }}" />

        <!-- Visit Type Select -->
        <flux:select wire:model="visit_type" label="{{ __('Visit Type') }}" placeholder="{{ __('Select Type') }}">
            <option value="">{{ __('Select Type') }}</option>
            @foreach($visitTypes as $type => $label)
                <option value="{{ $type }}">{{ $label }}</option>
            @endforeach
        </flux:select>

        <!-- Objective Textarea -->
        <flux:textarea wire:model="objective" label="{{ __('Objective') }}" placeholder="{{ __('Enter visit objective') }}" />

        <!-- Notes Textarea -->
        <flux:textarea wire:model="notes" label="{{ __('Notes') }}" placeholder="{{ __('Enter notes') }}" />

        <!-- Recommendations Textarea -->
        <flux:textarea wire:model="recommendations" label="{{ __('Recommendations') }}" placeholder="{{ __('Enter recommendations') }}" />

        <!-- Form Actions -->
        <div class="flex justify-end gap-2 pt-6 border-t">
            <flux:button 
                type="button" 
                variant="ghost"
                wire:click="cancel"
            >{{ __('Cancel') }}</flux:button>
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </div>
    </form>
</flux:modal>
