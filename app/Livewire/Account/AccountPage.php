<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class AccountPage extends Component
{
    use WithSeo;

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->setSeo(
            title: 'บัญชีของฉัน — PGMF Shop',
            description: 'จัดการบัญชีผู้ใช้ ตรวจสอบคำสั่งซื้อและที่อยู่จัดส่ง',
        );

        return $this->renderWithSeo('livewire.account.account-page');
    }
}
