<div>
    <x-header title="Space Management" subtitle="View, create, and manage your space" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Create Space" icon="o-home" wire:click="$toggle('ModalSpace')" spinner="ModalSpace" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-modal wire:model="ModalSpace" title="Create New Space" subtitle="Fill in the details to add a new space">
        <x-form wire:submit="createSpace" no-separator>

            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Name" icon="o-home" placeholder="Enter space name" wire:model="name" />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Location" icon="o-map-pin" placeholder="Enter space location" wire:model="location" />
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('ModalSpace')" />
                <x-button label="Confirm" wire:submit="createSpace" type="submit" spinner="createSpace" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-card>
        <x-table :headers="$headers" :rows="$spaces">
            @scope('actions', $space)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="edit({{ $space->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="delete({{ $space->id }})" spinner class="btn-sm btn-error" />
            </div>
            @endscope
        </x-table>
    </x-card>
</div>
