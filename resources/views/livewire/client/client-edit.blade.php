<div>
    <x-card title="Client Details" subtitle="Manage and configure your client information" shadow separator>
        <x-form wire:submit="update">
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Name" icon="o-user" placeholder="The full name" wire:model="name"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Email" icon="o-envelope" placeholder="The e-mail" wire:model="email"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="Phone" icon="o-phone" placeholder="The phone number" wire:model="phone"/>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12">
                    <x-input label="NIF" icon="o-identification" placeholder="The NIF" wire:model="vat_number"/>
                </div>
            </div>
            <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
            <x-slot:actions>
                <x-button label="Cancel" icon="o-x-mark" link="{{ route('admin.clients') }}" class="btn-sm" />
                <x-button label="Save Changes" icon="o-check" type="submit" spinner="update" class="btn btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>