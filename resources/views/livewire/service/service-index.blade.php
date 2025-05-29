<div>
    <x-header title="Service Management" subtitle="View, create, and manage your service" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Create Service" icon="o-sparkles" wire:click="$toggle('ModalService')" spinner="ModalService" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-header>

    <x-modal wire:model="ModalService" title="Create New Service" subtitle="Fill in the details to add a new service">
        <x-form wire:submit="createService" no-separator>

            <div class="row mb-4">
                <div class="col-span-12 py-3">
                    <x-input label="Name" icon="o-sparkles" placeholder="Enter name" wire:model.defer="name" />
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-span-12 py-3">
                    <x-textarea label="Description" icon="o-information-circle" wire:model.defer="description" placeholder="Enter description" hint="Max 250 chars" rows="2" />
                </div>
            </div>

            @foreach ($spaceInputs as $index => $input)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-end">
                    <div class="w-full">
                        <label for="space-{{ $index }}" class="block text-sm font-medium text-gray-700">{{ __('Spaces') }}</label>
                        <select id="space-{{ $index }}" wire:model="spaceInputs.{{ $index }}.space_id" class="select w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"                       >
                            <option value="" disabled selected>{{ __('Select a space') }}</option>
                            @foreach($spaces as $space)
                                <option value="{{ $space['value'] ?? $space['id'] ?? '' }}">
                                    {{ $space['label'] ?? $space['name'] ?? '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full">
                        <x-input label="Price" wire:model.defer="spaceInputs.{{ $index }}.price" prefix="EUR" />
                    </div>
                    <div class="w-full">
                        <x-input label="Commission (%)" wire:model.defer="spaceInputs.{{ $index }}.commission_percentage" suffix="%" />
                    </div>
                    <div class="w-full">
                        <x-button label="Remove" icon="o-trash" wire:click="removeSpaceInput({{ $index }})" class="btn-sm btn-error" />
                    </div>
                </div>
            @endforeach

            <div class="row mb-4">
                <div class="col-span-12 py-3 flex justify-end">
                    <x-button label="Add Space" icon="o-home" class="btn-sm" spinner="addSpaceInput" wire:click="addSpaceInput" />
                </div>
            </div>

            <x-slot:actions>
                <x-button label="Cancel" wire:click="$toggle('ModalService')" />
                <x-button label="Confirm" wire:submit="createService" type="submit" spinner="createService" icon="o-paper-airplane" class="btn-primary" />
            </x-slot:actions>
        </x-form>
    </x-modal>

    <x-card>
        <x-table :headers="$headers" :rows="$services">
            @scope('actions', $service)
            <div class="flex gap-2">
                <x-button icon="o-pencil" wire:click="edit({{ $service->id }})" spinner class="btn-sm" />
                <x-button icon="o-trash" wire:click="delete({{ $service->id }})" spinner class="btn-sm btn-error" />
            </div>
            @endscope
        </x-table>
    </x-card>
</div>