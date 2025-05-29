<?php

namespace App\Livewire\Staff;
use App\Models\Staff;
use App\Models\Service;
use Livewire\Component;
use Mary\Traits\Toast;

class StaffEdit extends Component
{

    use Toast;

    public $staff, $name, $role, $email, $profile_photo_url, $services;
    public $services_staff = [];

    public function mount($id)
    {
        $this->staff = Staff::find($id);
        $this->name = $this->staff->name;
        $this->role = $this->staff->role;
        $this->email = $this->staff->email;
        $this->profile_photo_url = $this->staff->profile_photo_url;
        $this->services_staff = $this->staff->services->pluck('id')->toArray();
        $this->services = Service::all();
    }

    public function back()
    {
        return redirect()->route('admin.staffs');
    }

    public function update()
    {
        try {

            $this->validate([
                'name' => 'required|string|max:255',
                'role' => 'required',
                'email' => 'required|email',
                'services_staff' => 'required|array',
            ],
            [
                'name.required' => 'Name is required.',
                'role.required' => 'Role is required.',
                'email.required' => 'Email is required.',
                'email.email' => 'Email must be a valid email address.',
                'services_staff.required' => 'At least one service must be selected.',
            ]);

            $this->staff->update([
                'name' => $this->name,
                'role' => $this->role,
                'email' => $this->email,
                'profile_photo_url' => $this->profile_photo_url,
            ]);

            $this->staff->services()->sync($this->services_staff);
            $this->back();
            $this->success('Staff updated successfully!');
            
        } catch (\Exception $e) {
            $this->error('Error updating staff: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.staff.staff-edit');
    }
}