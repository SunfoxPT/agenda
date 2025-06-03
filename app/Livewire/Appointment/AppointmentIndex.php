<?php

namespace App\Livewire\Appointment;

use Livewire\Component;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\BusinessHour;
use Carbon\Carbon;

class AppointmentIndex extends Component
{

    public $events;
    public $feedbackMessage;
    public $feedbackType;
    public $businessHours = [];

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

        $this->businessHours = BusinessHour::all()->map(function ($item) {
            return [
                'daysOfWeek' => [(int) $item->day_of_week],
                'startTime' => $item->start_time,
                'endTime' => $item->end_time,
            ];
        });
    }

    public function UpdateDragAndDrop(Request $request, $event_id)
    {
        $appointment = Appointment::findOrFail($event_id);

        $scheduledAt = Carbon::parse($request->input('start'));
        $endAt = $request->input('end') != null
            ? Carbon::parse($request->input('end'))
            : $scheduledAt->copy()->addMinutes(30);

        $dayOfWeek = $scheduledAt->dayOfWeek;

        $businessHours = BusinessHour::where('day_of_week', $dayOfWeek)->get();

        $isWithinBusinessHours = $businessHours->contains(function ($slot) use ($scheduledAt, $endAt) {
            return
                $scheduledAt->format('H:i') >= Carbon::parse($slot->start_time)->format('H:i') &&
                $endAt->format('H:i') <= Carbon::parse($slot->end_time)->format('H:i');
        });

        if (! $isWithinBusinessHours) {
            return response()->json([
                'error' => 'The selected time is outside of business hours for that day.'
            ], 422);
        }

        try {
            $appointment->scheduled_at = $scheduledAt->toDateTimeString();
            $appointment->end_at = $endAt->toDateTimeString();
            $appointment->save();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update appointment: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => 'Appointment updated successfully!']);
    }


    public function render()
    {
        return view('livewire.appointment.appointment-index');
    }
}
