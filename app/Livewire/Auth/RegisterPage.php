<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Mail\WelcomeMail;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class RegisterPage extends Component
{
    use WithSeo;

    public $mode = 'choose';
    public $name = '';
    public $email = '';
    public $password = '';
    public $confirmPassword = '';
    public $showPassword = false;

    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    public function register()
    {
        if (!$this->name || !$this->email || !$this->password) {
            $this->dispatch('toast', message: 'กรุณากรอกข้อมูลให้ครบ', type: 'error');
            return;
        }
        if ($this->password !== $this->confirmPassword) {
            $this->dispatch('toast', message: 'รหัสผ่านไม่ตรงกัน', type: 'error');
            return;
        }
        if (strlen($this->password) < 6) {
            $this->dispatch('toast', message: 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร', type: 'error');
            return;
        }
        if (User::where('email', $this->email)->exists()) {
            $this->dispatch('toast', message: 'อีเมลนี้ถูกใช้แล้ว', type: 'error');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'customer',
        ]);

        event(new Registered($user));
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Auth::login($user);
        session()->regenerate();
        $this->dispatch('toast', message: 'สมัครสมาชิกสำเร็จ!', type: 'success');
        return redirect('/');
    }

    public function render()
    {
        $this->setSeo(
            title: 'สมัครสมาชิก — PGMF Shop',
            description: 'สมัครสมาชิกเพื่อเริ่มต้นสั่งซื้อหนังสือคุณภาพจาก PGMF Shop',
        );

        return $this->renderWithSeo('livewire.auth.register-page');
    }
}
