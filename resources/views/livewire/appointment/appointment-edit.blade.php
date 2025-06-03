<div>
    <x-card title="Appointment Details" subtitle="Manage and configure your appointment information" shadow separator >
        <x-form wire:submit="update" class="space-y-6">

            <div class="border border-base-300 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-icon name="o-user-circle" class="text-primary" />
                    Client Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-input label="Client Name" icon="o-user" value="{{ $appointment->client->name }}" readonly />
                    <x-input label="Client Email" icon="o-envelope" value="{{ $appointment->client->email }}" readonly />
                    <x-input label="Client Phone" icon="o-phone" value="{{ $appointment->client->phone }}" readonly />
                    <x-input label="Client NIF" icon="o-identification" value="{{ $appointment->client->vat_number }}" readonly />
                </div>
            </div>

            <div class="border border-base-300 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-icon name="o-calendar-days" class="text-primary" />
                    Appointment Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-datetime label="Date Start" wire:model="time_start" type="datetime-local" />
                    <x-datetime label="Date End" wire:model="time_end" type="datetime-local" />
                </div>

                <div class="divider divider-primary"></div>
       
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <x-icon name="o-squares-plus" class="text-primary" />
                        Services
                    </h3>
                    <x-button label="Add Service" icon="o-plus" class="btn-primary btn-sm" spinner="addService" wire:click="addService" />
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($serviceItems as $index => $serviceItem)
                    <x-card class="border border-base-300 hover:border-primary transition-colors duration-200" shadow="sm">
                        <x-button icon="o-trash" title="Remove service" class="btn-error btn-sm absolute top-2 right-2" spinner="removeService({{ $index }})" wire:click="removeService({{ $index }})" />
                        <div class="space-y-3">
                            <fieldset class="fieldset p-0">
                                @php
                                    $selectedServiceId = $serviceItem['service_id'] ?? null;
                                    $isEditMode = $serviceItem['edit_mode'] ?? false;
                                    $selectedServiceName = $selectedServiceId ? \App\Models\Service::find($selectedServiceId)->name ?? '' : '';
                                @endphp

                                @if ($selectedServiceId && !$isEditMode)
                                    <x-input label="Service" icon="o-sparkles" value="{{ $selectedServiceName }}" readonly /> 
                                @else
                                    <legend class="fieldset-legend">Service</legend>
                                    <select wire:change="selectService($event.target.value, {{ $index }})" class="select select-bordered w-full mt-2">
                                        <option value="" disabled {{ !$selectedServiceId ? 'selected' : '' }}>
                                            Select a service
                                        </option>
                                        @foreach($services as $service)
                                            @php
                                                $disabled = $this->isServiceUsed($service->id, $index);
                                                $isSelected = $selectedServiceId == $service->id;
                                            @endphp
                                            <option value="{{ $service->id }}" {{ $isSelected ? 'selected' : '' }} {{ $disabled ? 'disabled' : '' }}                                           >
                                                {{ $service->name }}
                                            </option>
                                        @endforeach
                                    </select>	
                                @endif
                            </fieldset>

                            <fieldset class="fieldset p-0">
                                <legend class="fieldset-legend">Professional</legend>
                                @php
                                    $selectedStaffId = $serviceItem['staff_id'] ?? null;
                                    $isEditMode = $serviceItem['edit_mode'] ?? false;
                                    $selectedStaffName = $selectedStaffId ? \App\Models\Staff::find($selectedStaffId)->name ?? '' : '';
                                @endphp

                                @if ($selectedStaffId && !$isEditMode)
                                    <x-input label="Staff" icon="o-user-circle" value="{{ $selectedStaffName }}" readonly /> 
                                @else
                                    <select wire:change="selectStaff($event.target.value, {{ $index }})" class="select select-bordered w-full mt-2">
                                        <option value="" disabled {{ !$selectedStaffId ? 'selected' : '' }}>
                                            Select a Professional
                                        </option>
                                        @foreach($availableStaffList[$index] ?? [] as $staff)
                                            @php
                                                $isStaffSelected = $selectedStaffId == $staff->id;
                                            @endphp
                                            <option value="{{ $staff->id }}" {{ $isStaffSelected ? 'selected' : '' }}>
                                                {{ $staff->name }}
                                            </option>
                                        @endforeach
                                    </select>	
                                @endif
                            </fieldset>

                            <x-input label="Price" icon="o-currency-euro" value="€{{ number_format($serviceItem['price_charged'] ?? 0, 2, ',', '.') }}" readonly />
                            <x-input label="Commission" icon="o-presentation-chart-bar" value="{{ $serviceItem['commission_percentage'] ?? 0 }}% ({{ number_format($serviceItem['commission_value'] ?? 0, 2, ',', '.') }} €)" readonly />
                        </div>
                    </x-card>
                @endforeach
                </div>
            </div>

            <div class="border border-base-300 p-4 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-icon name="o-map-pin" class="text-primary" />
                    Location Information
                </h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <x-input label="Space" icon="o-home" value="{{ $space->name }}" readonly />              
                    <x-input label="Location" icon="o-map-pin" value="{{ $space->location }}" readonly />              
                </div>
            </div>
            
            <x-errors title="Oops!" description="Please fix the issues below." icon="o-face-frown" />
            <x-slot:actions>
            <div class="flex justify-between items-center mb-4 w-full">

                <div>
                    <x-button 
                        label="Delete Appointment" 
                        icon="o-trash" 
                        class="btn-error btn-sm" 
                        wire:click="deleteAppointment" 
                        spinner="deleteAppointment" 
                    />
                </div>

                <div class="flex space-x-2">
                    <x-button 
                        label="Cancel" 
                        icon="o-x-mark" 
                        link="{{ route('admin.appointments') }}" 
                        class="btn-sm" 
                    />
                    <x-button 
                        label="Save Changes" 
                        icon="o-check" 
                        wire:click="update" 
                        spinner="update" 
                        class="btn btn-primary" 
                    />
                </div>
            </div>
        </x-slot:actions>

        </x-form>
    </x-card>
</div>