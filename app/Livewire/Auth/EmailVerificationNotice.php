<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EmailVerificationNotice extends Component
{
    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        auth()->user()->sendEmailVerificationNotification();
        $this->dispatch('toast', message: 'ส่งอีเมลยืนยันใหม่แล้ว กรุณาตรวจสอบอีเมลของคุณ', type: 'success');
    }

    public function render()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect('/');
        }

        return view('livewire.auth.email-verification-notice');
    }
}
