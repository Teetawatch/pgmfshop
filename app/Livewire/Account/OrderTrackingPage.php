<?php

namespace App\Livewire\Account;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Order;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class OrderTrackingPage extends Component
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

    public function getThaiPostTrackingUrl(): string
    {
        if (!$this->order->tracking_number) {
            return '';
        }
        return 'https://track.thailandpost.co.th/?trackNumber=' . urlencode($this->order->tracking_number);
    }

    public function render()
    {
        $this->setSeo(
            title: "ติดตามพัสดุ {$this->order->order_number} — PGMF Shop",
            description: "ติดตามสถานะการจัดส่งคำสั่งซื้อ {$this->order->order_number}",
        );

        return $this->renderWithSeo('livewire.account.order-tracking-page', [
            'thaiPostUrl' => $this->getThaiPostTrackingUrl(),
        ]);
    }
}
