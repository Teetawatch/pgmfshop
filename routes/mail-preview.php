<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Order;
use App\Mail\WelcomeMail;
use App\Mail\OrderConfirmationMail;
use App\Mail\PaymentSuccessMail;
use App\Mail\ShippingNotificationMail;
use App\Mail\OrderCancelledMail;

/**
 * Mail preview routes — local/dev only
 */
Route::prefix('mail-preview')->group(function () {

    Route::get('/', function () {
        return '<h2>Mail Preview</h2><ul>'
            . '<li><a href="/mail-preview/welcome">Welcome</a></li>'
            . '<li><a href="/mail-preview/order-confirmation">Order Confirmation</a></li>'
            . '<li><a href="/mail-preview/payment-success">Payment Success</a></li>'
            . '<li><a href="/mail-preview/shipping-notification">Shipping Notification</a></li>'
            . '<li><a href="/mail-preview/order-cancelled">Order Cancelled</a></li>'
            . '</ul>';
    });

    Route::get('/welcome', function () {
        $user = User::first();
        return new WelcomeMail($user);
    });

    Route::get('/order-confirmation', function () {
        $order = Order::with(['items', 'user'])->latest()->first();
        if (!$order) return 'No orders found. Place an order first.';
        return new OrderConfirmationMail($order);
    });

    Route::get('/payment-success', function () {
        $order = Order::with(['items', 'user'])->latest()->first();
        if (!$order) return 'No orders found.';
        return new PaymentSuccessMail($order);
    });

    Route::get('/shipping-notification', function () {
        $order = Order::with(['items', 'user'])->latest()->first();
        if (!$order) return 'No orders found.';
        $order->tracking_number = $order->tracking_number ?: 'TH1234567890';
        return new ShippingNotificationMail($order);
    });

    Route::get('/order-cancelled', function () {
        $order = Order::with(['items', 'user'])->latest()->first();
        if (!$order) return 'No orders found.';
        return new OrderCancelledMail($order, 'สินค้าหมดสต็อก - ขออภัยในความไม่สะดวก');
    });
});
