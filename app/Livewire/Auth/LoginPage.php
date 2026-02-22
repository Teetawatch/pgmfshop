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
        if (!$this->email || !$this->password) {
            $this->dispatch('toast', message: 'กรุณากรอกข้อมูลให้ครบ', type: 'error');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            session()->regenerate();
            $this->dispatch('toast', message: 'เข้าสู่ระบบสำเร็จ!', type: 'success');
            return redirect()->intended('/');
        }

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
