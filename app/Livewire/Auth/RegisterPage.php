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
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8',
                'confirmPassword' => 'required|same:password',
            ], [
                'name.required' => 'กรุณากรอกชื่อ',
                'name.max' => 'ชื่อต้องไม่เกิน 255 ตัวอักษร',
                'email.required' => 'กรุณากรอกอีเมล',
                'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                'email.unique' => 'อีเมลนี้ถูกใช้แล้ว',
                'password.required' => 'กรุณากรอกรหัสผ่าน',
                'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'confirmPassword.same' => 'รหัสผ่านไม่ตรงกัน',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);
        $user->role = 'customer';
        $user->save();

        try {
            event(new Registered($user));
        } catch (\Exception $e) {
            \Log::error('Failed to send verification email: ' . $e->getMessage());
        }
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
