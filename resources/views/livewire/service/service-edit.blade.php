<div>
        <x-card title="Service Details" subtitle="Manage and configure your service information" shadow separator>

        <div class="row mb-4">
            <div class="col-span-12">
                <x-input label="Service Name" icon="o-sparkles" placeholder="Enter the service name" wire:model.defer="name" />
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-span-12">
                <x-textarea label="Service Description" icon="o-information-circle" wire:model.defer="description" placeholder="Provide a brief description of the service" hint="Maximum 250 characters" rows="2" />
            </div>
        </div>
        <div class="flex w-full flex-col">
            <div class="divider divider-primary">Pricing per Space</div>
        </div>

        @foreach ($spaceInputs as $index => $input)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 items-end">
                <div class="w-full">
                    <fieldset class="fieldset p-0">
                        <legend class="fieldset-legend">Space</legend>
                        <select id="space-{{ $index }}" wire:model="spaceInputs.{{ $index }}.space_id"
                            class="select w-full rounded border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" disabled selected>Select a space</option>
                            @foreach($spaces as $space)
                                @php
                                    $disabled = $this->isSpaceUsed($space->id, $index);
                                @endphp
                                <option 
                                    value="{{ $space->id }}" 
                                    {{ $input['space_id'] == $space->id ? 'selected' : '' }} 
                                    {{ $disabled ? 'disabled' : '' }}
                                >
                                    {{ $space->name }}
                                </option>
                            @endforeach
                        </select>
                    </fieldset>
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

        @if($this->hasAvailableSpaces)
            <div class="row mb-4">
                <div class="col-span-12 py-3 flex justify-end">
                    <x-button label="Add Space" icon="o-home" class="btn-sm" spinner="addSpaceInput" wire:click="addSpaceInput" />
                </div>
            </div>
        @endif
        <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
        <x-slot:actions>
            <x-button label="Cancel" icon="o-x-mark" link="{{ route('admin.services') }}" class="btn-sm" />
            <x-button label="Save" icon="o-check" wire:click="update" spinner="update" class="btn-sm btn-primary" />
        </x-slot:actions>
    </x-card>
</div>