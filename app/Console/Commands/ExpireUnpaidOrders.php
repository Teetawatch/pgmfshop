<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Product;
use App\Models\StockMovement;
use App\Mail\OrderCancelledMail;
use Illuminate\Support\Facades\Mail;

class ExpireUnpaidOrders extends Command
{
    protected $signature = 'orders:expire-unpaid';
    protected $description = 'Expire unpaid orders past their payment deadline and restore stock';

    public function handle(): int
    {
        $orders = Order::where('status', 'awaiting_payment')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '<=', now())
            ->get();

        if ($orders->isEmpty()) {
            $this->info('No expired orders found.');
            return self::SUCCESS;
        }

        $count = 0;
        $reason = 'ไม่ชำระเงินภายใน 24 ชั่วโมงตามเวลาที่กำหนด';

        foreach ($orders as $order) {
            $order->load(['items', 'user']);

            // Restore stock with audit trail
            foreach ($order->items as $item) {
                $p = Product::find($item->product_id);
                if ($p) {
                    $p->adjustStock(
                        $item->quantity,
                        StockMovement::TYPE_RETURN,
                        $reason,
                        StockMovement::REF_ORDER,
                        $order->order_number
                    );
                    $p->decrement('sold', $item->quantity);
                }
            }

            // Update order status to expired
            $order->addStatusHistory('expired', $reason);

            // Send cancellation email
            if ($order->user) {
                try {
                    Mail::to($order->user->email)->send(new OrderCancelledMail($order, $reason));
                } catch (\Exception $e) {
                    \Log::error('Failed to send order expiry email: ' . $e->getMessage());
                }
            }

            $count++;
        }

        $this->info("Expired {$count} unpaid order(s) and restored stock.");

        return self::SUCCESS;
    }
}
