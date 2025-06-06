<?php

namespace App\Livewire\Space;

use App\Models\Space;
use App\Models\Service;
use App\Models\Appointment;
use Livewire\Component;
use Illuminate\Http\Request;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\DB;

class SpaceIndex extends Component
{
    use Toast;

    public bool $ModalSpace = false;
    public $spaces, $name, $location;
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'bg-primary/50 w-1'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'location', 'label' => 'Location'],
    ];

    public function mount()
    {
        $this->spaces = Space::all();
    }


    public function render()
    {
        return view('livewire.space.space-index');
    }

    public function createSpace(Request $request)
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255'
        ]);
        
        try {

            Space::create([
                'name' => $this->name,
                'location' => $this->location
            ]);

            $this->success('Space created successfully!');
            $this->reset('name', 'location');
            $this->mount();

        } catch (\Exception $e) {
            $this->error('Error creating space: ' . $e->getMessage());
        }

        $this->ModalSpace = false;

    }

    public function delete($id)
    {
        try {
            $space = Space::findOrFail($id);
            $space->appointments()->delete();
            DB::table('space_service_prices')->where('space_id', $space->id)->delete();
            $space->delete();

            $this->mount();
            $this->success('Space deleted successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('Validation error: ' . $e->getMessage());
            return;
        }
    }

    public function edit($id)
    {
        return redirect()->route('admin.spaces.edit', $id);
    }
}