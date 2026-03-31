<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use App\Models\User;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class ResetPasswordPage extends Component
{
    use WithSeo;

    #[Url]
    public string $token = '';

    #[Url]
    public string $email = '';

    public string $password = '';
    public string $password_confirmation = '';
    public bool $done = false;

    public function resetPassword()
    {
        try {
            $this->validate([
                'token'                 => 'required',
                'email'                 => 'required|email|max:255',
                'password'              => 'required|min:8|confirmed',
                'password_confirmation' => 'required',
            ], [
                'password.required'              => 'กรุณากรอกรหัสผ่านใหม่',
                'password.min'                   => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
                'password.confirmed'             => 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน',
                'password_confirmation.required' => 'กรุณายืนยันรหัสผ่าน',
                'email.required'                 => 'ไม่พบข้อมูลอีเมล',
                'email.email'                    => 'รูปแบบอีเมลไม่ถูกต้อง',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $firstError = collect($e->errors())->flatten()->first();
            $this->dispatch('toast', message: $firstError, type: 'error');
            return;
        }

        $status = Password::reset(
            [
                'email'                 => $this->email,
                'password'              => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token'                 => $this->token,
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->done = true;
        } elseif ($status === Password::INVALID_TOKEN) {
            $this->dispatch('toast', message: 'ลิงก์รีเซ็ตรหัสผ่านหมดอายุหรือไม่ถูกต้อง กรุณาขอลิงก์ใหม่', type: 'error');
        } else {
            $this->dispatch('toast', message: 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง', type: 'error');
        }
    }

    public function render()
    {
        $this->setSeo(
            title: 'ตั้งรหัสผ่านใหม่ — PGMF Shop',
            description: 'ตั้งรหัสผ่านใหม่สำหรับบัญชี PGMF Shop',
        );

        return $this->renderWithSeo('livewire.auth.reset-password-page');
    }
}
