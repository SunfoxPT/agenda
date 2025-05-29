<?php

namespace App\Livewire\Service;

use App\Models\Service;
use App\Models\Space;
use Livewire\Component;
use Illuminate\Http\Request;
use Mary\Traits\Toast;

class ServiceIndex extends Component
{
    use Toast;

    public 
        $services, 
        $name, 
        $description,
        $price, 
        $spaces;

    public $spaceInputs = [];
    public $spaces_service = [];
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'bg-primary/50 w-1'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'description', 'label' => 'Description']
    ];

    public bool $ModalService = false;

    public function mount()
    {
        $this->services = Service::all();
        $this->spaces = Space::all();
        $this->spaceInputs[] = [
            'space_id' => '',
            'price' => null,
            'commission_percentage' => null,
        ];
    }

    public function render()
    {
        return view('livewire.service.service-index');
    }

    public function createService(Request $request)
    {
        try {
            $this->validate([
                'name' => 'required',
                'description' => 'nullable|string',
                'spaceInputs.*.space_id' => 'required|exists:spaces,id',
                'spaceInputs.*.price' => 'required|numeric|min:0',
                'spaceInputs.*.commission_percentage' => 'required|numeric|min:0|max:100',
            ], [
                'name.required' => 'The name field is required.',
                'spaceInputs.*.space_id.required' => 'Please select a space for the service.',
                'spaceInputs.*.price.required' => 'The price field is required.',
                'spaceInputs.*.commission_percentage.required' => 'The commission percentage field is required.',
            ]);

            $service = Service::create([
                'name' => $this->name,
                'description' => $this->description,
            ]);

            foreach ($this->spaceInputs as $input) {
                if (!empty($input['space_id']) && isset($input['price'], $input['commission_percentage'])) {
                    $service->spaces()->syncWithoutDetaching([
                        $input['space_id'] => [
                            'price' => $input['price'],
                            'commission_percentage' => $input['commission_percentage'],
                        ],
                    ]);
                }
            }
    
            $this->ModalService = false;
            $this->success('Service created successfully!');
            return redirect()->route('admin.services');

        } catch (\Exception $e) {
            $this->ModalService = false;
            $this->error('Error creating service: ' . $e->getMessage());
        }
    }

    public function addSpaceInput()
    {
        $this->spaceInputs[] = [
            'space_id' => '',
            'price' => null,
            'commission_percentage' => null,
        ];
    }

    public function removeSpaceInput($index)
    {
        if (count($this->spaceInputs) > 1) {
            unset($this->spaceInputs[$index]);
            $this->spaceInputs = array_values($this->spaceInputs);
        }
    }

    public function delete($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();
            $this->success('Service deleted successfully!');
            return redirect()->route('admin.services');
        } catch (\Exception $e) {
            $this->error('Error deleting service: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        return redirect()->route('admin.services.edit', $id);
    }
}
