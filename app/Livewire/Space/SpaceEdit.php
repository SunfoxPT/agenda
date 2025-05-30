<?php

namespace App\Livewire\Space;

use Livewire\Component;
use App\Models\Space;
use Mary\Traits\Toast;

class SpaceEdit extends Component
{
    use Toast;

    public $space, $name, $location;

    public function mount($id)
    {
        $this->space = Space::find($id);
        $this->name = $this->space->name;
        $this->location = $this->space->location;
    }

    public function render()
    {
        return view('livewire.space.space-edit');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255'
        ]);

        try {
            $this->space->update([
                'name' => $this->name,
                'location' => $this->location
            ]);

            $this->success('Space updated successfully!');
            return redirect()->route('admin.spaces');

        } catch (\Exception $e) {
            $this->error('Error updating space: ' . $e->getMessage());
        }
    }

}
