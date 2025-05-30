<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

class Register extends Component
{
    use Toast;

    public string $name;
    public string $email;
    public string $password;
    public string $confirm_password;

    public function register()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ], [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'confirm_password.required' => 'Please confirm your password.',
            'confirm_password.same' => 'The confirmation password does not match.',
            'confirm_password.min' => 'The confirmation password must be at least 6 characters.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        try {

            $user = User::create(['name' => $this->name, 'email' => $this->email, 'password' => Hash::make($this->password)]);
            
            $this->success('Registration successfull 😊 !');

            event(new Registered($user));

            Auth::login($user);

            return redirect()->route('verification.notice');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->error('Registration failed!');
            return;
        }
        
    }

    public function cancelar()
    {
        $this->reset();
    }

    public function render()
    {
        return view('livewire.auth.register')
        ->layout('components.layouts.guest');
    }
}

