<div>
    <x-card title="Space Details" subtitle="Manage and configure your space information" shadow separator>
        <x-toast />
        <x-errors title="Oops!" description="Please correct the issues below." icon="o-face-frown" />

        <div class="row mb-4">
            <div class="col-span-12">
                <x-input label="Name" icon="o-home" placeholder="The full name" wire:model="name"/>   
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-span-12">
                <x-textarea label="Location" wire:model.defer="location" placeholder="Describe the location of the space" hint="Maximum 250 characters" rows="2" />
            </div>
        </div>
        <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
        <x-slot:actions>
            <x-button label="Cancel" icon="o-x-mark" link="{{ route('admin.spaces') }}" class="btn-sm" />
            <x-button label="Save" icon="o-check" wire:click="update" spinner="update" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-card>
</div>

