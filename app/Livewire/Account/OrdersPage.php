<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class OrdersPage extends Component
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

        $orders = Order::where('user_id', auth()->id())
            ->with('items')
            ->orderByDesc('created_at')
            ->get();

        $this->setSeo(
            title: 'คำสั่งซื้อของฉัน — PGMF Shop',
            description: 'ตรวจสอบสถานะคำสั่งซื้อและประวัติการสั่งซื้อ',
        );

        return $this->renderWithSeo('livewire.account.orders-page', [
            'orders' => $orders,
        ]);
    }
}
