<div>
    <x-card title="Staff Details" subtitle="Manage and configure your staff information" shadow separator>
        <x-form wire:submit="update">
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Name" icon="o-user" placeholder="The full name" wire:model.defer="name"/>   
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Email" icon="o-envelope" placeholder="The e-mail" wire:model.defer="email"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Role" icon="o-briefcase" placeholder="Enter role" wire:model.defer="role" />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-choices label="{{ __('Services') }}" icon="o-sparkles" wire:model.defer="services_staff" :options="$services" allow-all />
                </div>
            </div>
            <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
            <x-slot:actions>
                <x-button label="Cancel" icon="o-x-mark" link="{{ route('admin.staffs') }}" class="btn-sm" />
                <x-button label="Save" icon="o-check" type="submit" spinner="update" class="btn-sm btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>