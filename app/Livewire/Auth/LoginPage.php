<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class LoginPage extends Component
{
    use WithSeo;

    public $mode = 'choose';
    public $email = '';
    public $password = '';
    public $showPassword = false;

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function login()
    {
        try {
            $this->validate([
                'email' => 'required|string|email|max:255',
                'password' => 'required|string',
            ], [
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            return;
        }

        $throttleKey = strtolower($this->email) . '|' . request()->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            $this->dispatch('toast', message: "ลองเข้าสู่ระบบบ่อยเกินไป กรุณารอ {$seconds} วินาที", type: 'error');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            session()->regenerate();
            $this->dispatch('toast', message: 'เข้าสู่ระบบสำเร็จ!', type: 'success');
            return redirect()->intended('/');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey, 60);
        $this->dispatch('toast', message: 'อีเมลหรือรหัสผ่านไม่ถูกต้อง', type: 'error');
    }

    public function render()
    {
        $this->setSeo(
            title: 'เข้าสู่ระบบ — PGMF Shop',
            description: 'เข้าสู่ระบบเพื่อสั่งซื้อสินค้าจาก PGMF Shop',
        );

        return $this->renderWithSeo('livewire.auth.login-page');
    }
}
