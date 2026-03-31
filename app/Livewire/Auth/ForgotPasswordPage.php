<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class ForgotPasswordPage extends Component
{
    use WithSeo;

    public string $email = '';
    public bool $sent = false;

    public function sendLink()
    {
        try {
            $this->validate([
                'email' => 'required|email|max:255',
            ], [
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            return;
        }

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->sent = true;
        } elseif ($status === Password::RESET_THROTTLED) {
            $this->dispatch('toast', message: 'ส่งลิงก์ไปแล้ว กรุณารอสักครู่ก่อนส่งใหม่', type: 'warning');
        } else {
            $this->dispatch('toast', message: 'ไม่พบอีเมลนี้ในระบบ', type: 'error');
        }
    }

    public function render()
    {
        $this->setSeo(
            title: 'ลืมรหัสผ่าน — PGMF Shop',
            description: 'ขอลิงก์รีเซ็ตรหัสผ่านสำหรับบัญชี PGMF Shop',
        );

        return $this->renderWithSeo('livewire.auth.forgot-password-page');
    }
}
