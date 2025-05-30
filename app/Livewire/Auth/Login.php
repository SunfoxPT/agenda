<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Mary\Traits\Toast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class Login extends Component
{
    use Toast;

    public $email, $password;
    public $title = 'Login';

    public function render()
    {
        if (Auth::check() && Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('admin.staffs');
        }
        return view('livewire.auth.login')
        ->layout('components.layouts.guest');
    }

    public function authenticate(Request $request)
    {
        $credentials =  $this->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 6 characters.',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('admin.staffs');
        } else {
            $this->error('Invalid credentials. Please try again.');
        }
    }
}
