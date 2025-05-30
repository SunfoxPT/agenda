<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Http\Request;
use Mary\Traits\Toast;

class VerifyEmail extends Component
{
    use Toast;

    public function render()
    {
        return view('livewire.auth.verify-email')
        ->layout('components.layouts.guest');
    }

    public function resend()
    {
        try{
            auth()->user()->sendEmailVerificationNotification();
            $this->success('Email de verificação reenviado com sucesso! Verifique sua caixa de entrada ou pasta de spam.');
        } catch (\Exception $e) {
            $this->error('Erro ao reenviar o email de verificação.');
            return;
        }     

    }
}
