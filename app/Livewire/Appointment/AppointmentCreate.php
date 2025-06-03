<?php

namespace App\Livewire\Appointment;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\AppointmentServiceItem;
use App\Models\Client;
use App\Models\Space;
use App\Models\Service;
use App\Models\Staff;
use Mary\Traits\Toast;

class AppointmentCreate extends Component
{
    use Toast;

    public $appointment;
    public $title;
    public $clients;
    public $staffs;
    public $spaces;
    public $serviceItems = [];
    public $selectedClient;
    public $selectedSpace;
    public $services = [];
    public $selectedServices = null;
    public $availableStaffList = [];
    public $time_start;
    public $time_end;
    public $appointmentId;

    public function mount()
    {
        $this->appointment = new Appointment();
        $this->time_start = now()->format('Y-m-d\TH:i');
        $this->time_end = now()->addMinutes(30)->format('Y-m-d\TH:i');
        $this->clients = Client::all();
        $this->staffs = Staff::all();
        $this->spaces = Space::all();

        $this->serviceItems[] = [
            'service_id' => null,
            'staff_id' => null,
            'price_charged' => 0,
            'commission_percentage' => 0,
            'commission_value' => 0,
            'edit_mode' => false,
        ];
    }

    public function create(){

        $this->validate([
            'selectedClient.id' => 'required|exists:clients,id',
            'selectedSpace.id' => 'required|exists:spaces,id',
            'time_start' => 'required|date',
            'time_end' => 'required|date|after:time_start',
            'serviceItems.*.service_id' => 'nullable|exists:services,id',
            'serviceItems.*.staff_id' => 'nullable|exists:staff,id',
            'serviceItems.*.price_charged' => 'required|numeric|min:0',
            'serviceItems.*.commission_percentage' => 'required|numeric|min:0|max:100',
        ]);

        /*$conflictingAppointment = Appointment::where('space_id', $this->selectedSpace)
            ->where(function ($query) {
                $query->where('end_at', '>', $this->time_start)
                    ->where('scheduled_at', '<', $this->time_end);
            })
            ->first();

        if ($conflictingAppointment) {
            $spaceName = $conflictingAppointment->space->name ?? 'espaço selecionado';
            $this->addError('time_start', "Já existe um agendamento no horário para o espaço: {$spaceName} (ID: {$conflictingAppointment->space_id}).");
            return;
        }*/

        try {
            $this->appointment->fill([
                'client_id' => $this->selectedClient->id,
                'space_id' => $this->selectedSpace->id,
                'scheduled_at' => $this->time_start,
                'end_at' => $this->time_end,
            ]);
            $this->appointment->save();
        
        } catch (\Exception $e) {
            $this->addError('appointment', 'Failed to create appointment: ' . $e->getMessage());
            return;
        }

        try {
            foreach ($this->serviceItems as $item) {
                if ($item['service_id']) {
                    AppointmentServiceItem::create([
                        'appointment_id' => $this->appointment->id,
                        'service_id' => $item['service_id'],
                        'staff_id' => $item['staff_id'],
                        'price_charged' => $item['price_charged'],
                        'commission_percentage' => $item['commission_percentage'],
                        'commission_value' => round(($item['price_charged'] * $item['commission_percentage']) / 100, 2),
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->addError('service_items', 'Failed to create service items: ' . $e->getMessage());
            return;
        }

        $this->success('Appointment created successfully! 🎉');
        return redirect()->route('admin.appointments');
    }

    public function updateSelectedSpace($value)
    {
        $this->selectedSpace = Space::find($value);
        $this->updateServiceItemsForNewSpace();

        $this->services = Service::with('spaces')->get()->filter(function ($service) {
            return $service->spaces->contains($this->selectedSpace);
        })->values();
    }

    protected function updateServiceItemsForNewSpace()
    {
        if (!$this->selectedSpace) {
            return;
        }

        foreach ($this->serviceItems as $index => $item) {
            if (!$item['service_id']) {
                continue;
            }

            $service = Service::with('spaces')->find($item['service_id']);
            if (!$service) {
                unset($this->serviceItems[$index]);
                continue;
            }

            $space = $service->spaces->where('id', $this->selectedSpace->id)->first();

            if (!$space) {
                unset($this->serviceItems[$index]);
                continue;
            }

            $this->serviceItems[$index]['price_charged'] = $space->pivot->price;
            $this->serviceItems[$index]['commission_percentage'] = $space->pivot->commission_percentage;
            $this->serviceItems[$index]['commission_value'] = round(($space->pivot->price * $space->pivot->commission_percentage) / 100, 2);
            $this->serviceItems[$index]['edit_mode'] = true;

            $this->availableStaffList[$index] = $service->staff;
        }

        $this->serviceItems = array_values($this->serviceItems);
    }

    public function selectStaff($staffSelectedId, $index)
    {
        $this->serviceItems[$index]['staff_id'] = $staffSelectedId;
    }

    public function selectService($serviceSelectedId, $index)
    {
        $selectedService = Service::with(['staff', 'spaces'])->find($serviceSelectedId);

        if ($selectedService) {
            $space = $selectedService->spaces->where('id', $this->selectedSpace->id)->first();

            if ($space) {
                $this->serviceItems[$index] = [
                    'id' => null,
                    'service_id' => $selectedService->id,
                    'staff_id' => null,
                    'price_charged' => $space->pivot->price,
                    'commission_percentage' => $space->pivot->commission_percentage,
                    'commission_value' => round(($space->pivot->price * $space->pivot->commission_percentage) / 100, 2),
                    'edit_mode' => true,
                ];

                $this->availableStaffList[$index] = $selectedService->staff;
            }
        }

    }

    public function updateSelectedClient($value)
    {
        $this->selectedClient = Client::find($value);
    }

    public function addService()
    {
        $this->serviceItems[] = [
            'id' => null,
            'service_id' => null,
            'staff_id' => '',
            'price_charged' => 0,
            'commission_percentage' => 0,
            'commission_value' => 0,
            'edit_mode' => true,
        ];
    }

    public function isServiceUsed($serviceId, $currentIndex)
    {
        foreach ($this->serviceItems as $index => $item) {
            if ($index !== $currentIndex && $item['service_id'] && $item['service_id'] == $serviceId) {
                return true;
            }
        }
        return false;
    }

    public function render()
    {
        return view('livewire.appointment.appointment-create');
    }
}
