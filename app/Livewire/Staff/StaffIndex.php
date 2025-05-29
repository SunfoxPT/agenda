<?php

namespace App\Livewire\Staff;

use App\Models\Staff;
use App\Models\Service;
use Livewire\Component;
use Illuminate\Http\Request;
use Mary\Traits\Toast;

class StaffIndex extends Component
{

    use Toast;

    public $staffs, $name, $role, $email, $profile_photo_url, $services;
    public $services_staff = [];
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'bg-primary/50 w-1'],
        ['key' => 'profile_photo_url', 'label' => 'Photo'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'role', 'label' => 'Role'],
    ];

    public bool $ModalStaff = false;

    public function mount()
    {
        $this->staffs = Staff::all();
        $this->services = Service::all();
    }

    public function render()
    {
        return view('livewire.staff.staff-index');
    }

    public function edit($id)
    {
        return redirect()->route('admin.staffs.edit', $id);
    }

    public function createStaff(Request $request)
    {

        try {

            $this->validate([
                'name' => 'required',
                'role' => 'required',
                'email' => 'required|email',
                'services_staff' => 'required|array',
            ],
            [
                'name.required' => 'The name field is required.',
                'role.required' => 'The role field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'The email must be a valid email address.',
                'services_staff.required' => 'Please select at least one service for the staff.',
            ]);

            $staff = Staff::create([
                'name' => $this->name,
                'role' => $this->role,
                'email' => $this->email,
                'profile_photo_url' => $this->profile_photo_url,
            ]);

            $staff->services()->sync($this->services_staff);

            $this->success('Staff created successfully!');
            $this->reset('name', 'role', 'email', 'services_staff');
            $this->mount();
        } catch (\Exception $e) {
            $this->error('Error creating staff: ' . $e->getMessage());
        }

        $this->ModalStaff = false;

    }

    public function delete($id)
    {
        try {
            $staff = Staff::findOrFail($id)->delete();
            $this->mount();
            $this->success('Staff deleted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('Validation error: ' . $e->getMessage());
            return;
        }
    }
}