<div>
    <x-header title="Business Management" subtitle="" progress-indicator></x-header>
    <x-form wire:submit="save" no-separator>
        <div class="space-y-6">
            @foreach($daysOfWeek as $day => $label)
                <x-card Title="{{ $label }}" shadow separator class="p-4">

                    @foreach ($businessHours[$day] ?? [] as $index => $slot)
                        <div class="flex flex-col md:flex-row gap-4">
                            <x-datetime label="Start" wire:model="businessHours.{{ $day }}.{{ $index }}.start_time" type="time" />
                            <x-datetime label="End" wire:model="businessHours.{{ $day }}.{{ $index }}.end_time" type="time" />
                        </div>
                        
                        <div class="py-4">
                            <x-button icon="o-trash" title="Remove" class="btn-error btn-sm" spinner="removeTimeSlot({{ $day }}, {{ $index }})" wire:click.prevent="removeTimeSlot({{ $day }}, {{ $index }})" />
                        </div>
                    @endforeach

                    <x-button label="Add Time Slot"  icon="o-plus"  class=" btn-sm btn-primary" spinner="addTimeSlot({{ $day }})" wire:click.prevent="addTimeSlot({{ $day }})" />
                </x-card>
            @endforeach
        </div>

        <x-slot:actions>
            <x-button label="Cancel" icon="o-x-mark" link="{{ route('admin.appointments') }}" class="btn-sm" />
            <x-button label="Save Changes" type="submit" spinner="save" icon="o-paper-airplane" class="btn-primary" />
        </x-slot:actions>
    </x-form>
</div>
