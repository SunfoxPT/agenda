<div>
    <!-- HEADER -->
    <x-header title="Client Management" subtitle="View, create, and manage your client" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Create Client" icon="c-user-plus" wire:click="$toggle('ModalClient')" spinner="ModalClient" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-toast />  
    <x-errors title="Oops!" description="Please fix the following issues." icon="o-face-frown" />

    <x-modal wire:model="ModalClient" title="Create New Client" subtitle="Fill in the details to add a new client">
        <x-form wire:submit="createClient" no-separator>
            <x-input label="Name" icon="o-user" placeholder="The full name" wire:model="name"/>
            <x-input label="Email" icon="o-envelope" placeholder="The e-mail" wire:model="email"/>
            <x-input label="Phone" icon="o-phone" placeholder="The phone number" wire:model="phone"/>
            <x-input label="NIF" icon="o-identification" placeholder="The NIF" wire:model="vat_number"/>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('ModalClient')" />
                <x-button label="Confirm" wire:submit="createClient" type="submit" spinner="createClient" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-card>
        <x-table :headers="$headers" :rows="$clients">
            @scope('actions', $client)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="edit({{ $client->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="delete({{ $client->id }})" spinner class="btn-sm btn-error" />
            </div>
            @endscope
        </x-table>
    </x-card>
</div>