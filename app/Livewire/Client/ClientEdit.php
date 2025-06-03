<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Client;
use Mary\Traits\Toast;

class ClientEdit extends Component
{
    use Toast;

    public $client;
    public $name, $email, $phone, $vat_number;

    public function mount($client)
    {
        $this->client = Client::findOrFail($client);
        $this->name = $this->client->name;
        $this->email = $this->client->email;
        $this->phone = $this->client->phone;
        $this->vat_number = $this->client->vat_number;
    }

    public function update()
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

        try {
            $this->client->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'vat_number' => $this->vat_number,
            ]);
        } catch (\Exception $e) {
            $this->error('Error updating client: ' . $e->getMessage());
            return;
        }

        $this->success('Client updated successfully!');
        return redirect()->route('admin.clients');
    }

    public function render()
    {
        return view('livewire.client.client-edit');
    }
}
