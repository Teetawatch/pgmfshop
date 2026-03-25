<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $reason;

    public function __construct(public Order $order, string $reason = '')
    {
        $this->reason = $reason;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'คำสั่งซื้อ #' . $this->order->order_number . ' ถูกยกเลิก',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-cancelled',
        );
    }
}
