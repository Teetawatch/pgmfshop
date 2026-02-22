<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class OrderDetailPage extends Component
{
    use WithSeo;

    public Order $order;

    public function mount($id)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $this->order = Order::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('items')
            ->firstOrFail();
    }

    public function render()
    {
        $this->setSeo(
            title: "คำสั่งซื้อ {$this->order->order_number} — PGMF Shop",
            description: "รายละเอียดคำสั่งซื้อ {$this->order->order_number}",
        );

        return $this->renderWithSeo('livewire.account.order-detail-page');
    }
}
