<?php

namespace App\Livewire\Appointment;

use Livewire\Component;
use App\Models\Appointment;

class AppointmentIndex extends Component
{

    public $events;

    public function mount($event = null)
    {
        $this->events = Appointment::with(['client', 'space', 'serviceItems.service', 'serviceItems.staff'])
            ->orderBy('scheduled_at', 'asc')
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'title' => $appointment->client->name,
                    'start' => $appointment->scheduled_at,
                    'end' => $appointment->end_at,
                    'space' => $appointment->space->name,
                    'services' => $appointment->serviceItems->map(function ($item) {
                        return [
                            'service' => $item->service->name,
                            'staff' => $item->staff->name,
                            'price_charged' => $item->price_charged,
                        ];
                    }),
                ];
            });

    }

    public function render()
    {
        return view('livewire.appointment.appointment-index');
    }
}
