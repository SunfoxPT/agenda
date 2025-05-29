<div>
    <!-- HEADER -->
    <x-header title="Staff Management" subtitle="View, create, and manage your staff" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Create Staff" icon="c-user-plus" wire:click="$toggle('ModalStaff')" spinner="ModalStaff" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-toast />  
    <x-errors title="Oops!" description="Please fix the following issues." icon="o-face-frown" />

    <x-modal wire:model="ModalStaff" title="Create New Staff" subtitle="Fill in the details to add a new staff">
        <x-form wire:submit="createStaff" no-separator>
            <x-input label="Name" icon="o-user" placeholder="The full name" wire:model="name"/>
            <x-input label="Email" icon="o-envelope" placeholder="The e-mail" wire:model="email"/>
            <x-input label="Role" icon="o-briefcase" placeholder="Enter role" wire:model="role" />
            <x-choices label="{{ __('Services') }}" icon="o-sparkles" wire:model="services_staff" :options="$services" allow-all />

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('ModalStaff')" />
                <x-button label="Confirm" wire:submit="createStaff" type="submit" spinner="createStaff" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-card>
        <x-table :headers="$headers" :rows="$staffs">
            @scope('actions', $staffs)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="edit({{ $staffs->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="delete({{ $staffs->id }})" spinner class="btn-sm btn-error" />
            </div>
            @endscope
        </x-table>
    </x-card>
</div>
