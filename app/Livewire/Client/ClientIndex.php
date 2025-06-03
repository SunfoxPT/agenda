<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Client;
use Mary\Traits\Toast;

class ClientIndex extends Component
{
    use Toast;

    public $clients, $name, $email, $phone, $vat_number;
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'bg-primary/50 w-1'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'phone', 'label' => 'Phone'],
        ['key' => 'vat_number', 'label' => 'NIF'],
    ];

    public bool $ModalClient = false;

    public function mount()
    {
        $this->clients = Client::all();
    }

    public function edit($id)
    {
        return redirect()->route('admin.clients.edit', $id);
    }

    public function createClient()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'vat_number' => 'nullable',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'phone.required' => 'The phone field is required.',
        ]);

        try{
            Client::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'vat_number' => $this->vat_number,
            ]);
        } catch (\Exception $e) {
            $this->error('Error creating client: ' . $e->getMessage());
            return;
        }

        $this->reset(['name', 'email', 'phone', 'vat_number']);
        $this->ModalClient = false;

        $this->success('Client created successfully!');
        return redirect()->route('admin.clients');
    }

    public function delete($id)
    {
        $client = Client::find($id);
        if ($client) {
            try {
                $client->delete();
                $this->success('Client deleted successfully!');
            } catch (\Exception $e) {
                $this->error('Error deleting client: ' . $e->getMessage());
            }
        } else {
            $this->error('Client not found.');
        }
    }

    public function render()
    {
        return view('livewire.client.client-index');
    }
}
