<?php

namespace App\Livewire\Service;
use App\Models\Service;
use App\Models\Space;
use Livewire\Component;
use Mary\Traits\Toast;

class ServiceEdit extends Component
{
    use Toast;

    public $service;
    public $name;
    public $description;
    public $spaceInputs = [];
    public $spaces = [];

    public function mount($id)
    {
        $this->service = Service::with('spaces')->findOrFail($id);
        $this->name = $this->service->name;
        $this->description = $this->service->description;
        $this->spaceInputs = $this->service->spaces->map(function ($space) {
            return [
                'space_id' => $space->id,
                'price' => $space->pivot->price,
                'commission_percentage' => $space->pivot->commission_percentage,
            ];
        })->toArray();
        $this->spaces = Space::all();
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

    public function getHasAvailableSpacesProperty()
    {
        $usedSpaceIds = collect($this->spaceInputs)->pluck('space_id')->filter()->all();
    
        $availableSpaces = collect($this->spaces)->filter(function ($space) use ($usedSpaceIds) {
            return !in_array($space->id, $usedSpaceIds);
        });
    
        return $availableSpaces->count() >= 1;
    }

    public function isSpaceUsed($spaceId, $currentIndex)
    {
        foreach ($this->spaceInputs as $index => $input) {
            if ($index !== $currentIndex && $input['space_id'] == $spaceId) {
                return true;
            }
        }
        return false;
    }

    public function back()
    {
        return redirect()->route('admin.services');
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:250',
            'spaceInputs.*.space_id' => 'required|exists:spaces,id',
            'spaceInputs.*.price' => 'nullable|numeric|min:0',
            'spaceInputs.*.commission_percentage' => 'nullable|numeric|min:0|max:100',
        ]);
    
        try {

            $this->service->update([
                'name' => $this->name,
                'description' => $this->description,
            ]);
    
            $syncData = [];
    
            foreach ($this->spaceInputs as $input) {
                if (!empty($input['space_id'])) {
                    $syncData[$input['space_id']] = [
                        'price' => $input['price'] ?? 0,
                        'commission_percentage' => $input['commission_percentage'] ?? 0,
                    ];
                }
            }
    
            $this->service->spaces()->sync($syncData);
            $this->success('Serviço atualizado com sucesso!');
            return redirect()->route('admin.services');
    
        } catch (\Exception $e) {
            logger()->error('Erro ao atualizar serviço: ' . $e->getMessage());
            $this->error('Ocorreu um erro ao atualizar o serviço.');
        }
    }    

    public function render()
    {
        return view('livewire.service.service-edit');
    }
}