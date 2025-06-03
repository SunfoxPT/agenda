<?php

namespace App\Livewire\Appointment;

use Livewire\Component;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\AppointmentServiceItem;
use App\Models\Client;
use App\Models\Space;
use App\Models\Service;
use App\Models\Staff;
use Mary\Traits\Toast;

class AppointmentEdit extends Component
{
    use Toast;

    public $appointment;
    public $client;
    public $clients;
    public $staffs;
    public $space;
    public $serviceItems = [];
    public $services = [];
    public $selectedServices = null;
    public $availableStaffList = [];
    public $time_start;
    public $time_end;

    public function mount($appointment)
    {
        $this->appointment = Appointment::with([
            'client',
            'space',
            'serviceItems.service',
            'serviceItems.staff'
        ])->findOrFail($appointment);

        $this->client = $this->appointment->client;
        $this->space = $this->appointment->space;
        $this->services = Service::all();
        $this->time_start = $this->appointment->scheduled_at;
        $this->time_end = $this->appointment->end_at;
        $this->clients = Client::all();

        $this->serviceItems = $this->appointment->serviceItems->map(function ($item) {
            return [
                'id' => $item->id,
                'service_id' => $item->service->id,
                'staff_id' => $item->staff->id,
                'price_charged' => $item->price_charged,
                'commission_percentage' => $item->commission_percentage,
                'commission_value' => round( 
                    ($item->price_charged * $item->commission_percentage) / 100, 2
                ),
                'edit_mode' => false,
            ];
        })->toArray();
    }

    public function update(Request $request)
    {
        $this->validate([
            'client.id' => 'required|exists:clients,id',
            'space.id' => 'required|exists:spaces,id',
            'time_start' => 'required',
            'time_end' => 'required|after:time_start',
            'serviceItems.*.service_id' => 'required|exists:services,id',
            'serviceItems.*.staff_id' => 'nullable|exists:staff,id',
        ]);

        try {
            $this->appointment->client_id = $this->client->id;
            $this->appointment->space_id = $this->space->id;
            $this->appointment->scheduled_at = $this->time_start;
            $this->appointment->end_at = $this->time_end;
            $this->appointment->save();

            foreach ($this->serviceItems as $item) {
                AppointmentServiceItem::updateOrCreate(
                    ['id' => $item['id']],
                    [
                        'appointment_id' => $this->appointment->id,
                        'service_id' => $item['service_id'],
                        'staff_id' => $item['staff_id'] ?? null,
                        'price_charged' => $item['price_charged'],
                        'commission_percentage' => $item['commission_percentage'],
                        'commission_value' => $item['commission_value'],
                    ]
                );
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            $this->error('Error updating appointment: ' . $e->getMessage());
            return;
        }

        $this->success('message', 'Appointment updated successfully.');
    }

    public function selectService($serviceSelectedId, $index)
    {
        $selectedService = Service::with(['staff', 'spaces'])->find($serviceSelectedId);

        if ($selectedService) {
            $space = $selectedService->spaces->where('id', $this->space->id)->first();

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

    public function selectStaff($staffSelectedId, $index)
    {
        $this->serviceItems[$index]['staff_id'] = $staffSelectedId;
    }

   public function removeService($index)
    {
        $this->validate([
            'client.id' => 'required|exists:clients,id',
            'space.id' => 'required|exists:spaces,id',          
        ]);

        if (!isset($this->serviceItems[$index])) {
            $this->error('Item de serviço não encontrado.');
            return;
        }

        if (count($this->serviceItems) <= 1) {
            $this->error('Deve haver pelo menos um serviço.');
            return;
        }

        if (isset($this->serviceItems[$index]['id'])) {
            try {
               AppointmentServiceItem::where('id', $this->serviceItems[$index]['id'])->delete();
            } catch (\Exception $e) {
                $this->error('Erro ao remover o serviço: ' . $e->getMessage());
                return;
            }
            
        }

        unset($this->availableStaffList[$index]);
        unset($this->serviceItems[$index]);

        $this->serviceItems = array_values($this->serviceItems);
        $this->availableStaffList = array_values($this->availableStaffList);

        $this->success('Serviço removido com sucesso!');
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

    public function deleteAppointment()
    {
        $this->validate([
            'appointment.id' => 'required|exists:appointments,id',
        ]);

        try {
            AppointmentServiceItem::where('appointment_id', $this->appointment->id)->delete();
            $this->appointment->delete();
        } catch (\Exception $e) {
            $this->error('Error deleting appointment: ' . $e->getMessage());
            return;
        }
        
        $this->success('message', 'Appointment deleted successfully.');
        return redirect()->route('admin.appointments');
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

    public function render()
    {
        return view('livewire.appointment.appointment-edit');
    }
}
