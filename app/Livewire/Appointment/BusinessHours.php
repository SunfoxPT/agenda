<?php

namespace App\Livewire\Appointment;

use Livewire\Component;
use App\Models\BusinessHour;
use Mary\Traits\Toast;

class BusinessHours extends Component
{
    use Toast;

    public $businessHours = [];
    public $daysOfWeek = [
        0 => 'Sunday',
        1 => 'Monday',
        2 => 'Tuesday',
        3 => 'Wednesday',
        4 => 'Thursday',
        5 => 'Friday',
        6 => 'Saturday',
    ];

    public function mount()
    {
        $this->businessHours = [];

        foreach (BusinessHour::all() as $hour) {
            $this->businessHours[$hour->day_of_week][] = [
                'start_time' => $hour->start_time,
                'end_time' => $hour->end_time,
            ];
        }
    }

    public function addTimeSlot($day)
    {
        $this->businessHours[$day][] = ['start_time' => '', 'end_time' => ''];
    }

    public function removeTimeSlot($day, $index)
    {
        unset($this->businessHours[$day][$index]);
        $this->businessHours[$day] = array_values($this->businessHours[$day]);
    }

    public function save()
    {
        try{
            BusinessHour::truncate();

            foreach ($this->businessHours as $day => $slots) {
                foreach ($slots as $slot) {
                    if ($slot['start_time'] && $slot['end_time']) {
                        BusinessHour::create([
                            'day_of_week' => $day,
                            'start_time' => $slot['start_time'],
                            'end_time' => $slot['end_time'],
                        ]);
                    }
                }
            }

            $this->success('Business hours updated successfully!');
        } catch (\Exception $e) {
            $this->error('Error updating business hours: ' . $e->getMessage());
            return;
        }
        
    }

    public function render()
    {
        return view('livewire.appointment.business-hours');
    }
}
