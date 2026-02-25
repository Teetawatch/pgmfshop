<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductManageController;
use App\Http\Controllers\Admin\OrderManageController;
use App\Http\Controllers\Admin\CustomerManageController;
use App\Http\Controllers\Admin\CouponManageController;
use App\Http\Controllers\Admin\CategoryManageController;
use App\Http\Controllers\Admin\BannerManageController;
use App\Http\Controllers\Admin\StockManageController;
use App\Http\Controllers\Admin\ShippingRateController as ShippingRateCtrl;
use App\Http\Controllers\Admin\ReviewManageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ContactManageController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PromptPayQRController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// ─── Mail Preview (local only) ───
if (app()->environment('local')) {
    require __DIR__ . '/mail-preview.php';
}

// ─── Storefront (Livewire) ───
Route::get('/', App\Livewire\HomePage::class)->name('home');
Route::get('/products', App\Livewire\ProductsPage::class)->name('products');
Route::get('/products/{slug}', App\Livewire\ProductDetail::class)->name('products.show');
Route::get('/cart', App\Livewire\CartPage::class)->name('cart');
Route::get('/promptpay-qr', [PromptPayQRController::class, 'generate'])->name('promptpay.qr');

// Static Pages
Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('auth')->name('contact.store');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/how-to-order', 'pages.how-to-order')->name('how-to-order');
Route::view('/privacy', 'pages.privacy')->name('privacy');

// Auth pages (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', App\Livewire\Auth\LoginPage::class)->name('login');
    Route::get('/register', App\Livewire\Auth\RegisterPage::class)->name('register');
});

// Social Auth (Google, Facebook)
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// Email Verification
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', App\Livewire\Auth\EmailVerificationNotice::class)->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('verified', true);
    })->middleware('signed')->name('verification.verify');
});

// Checkout & Account (auth required)
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', App\Livewire\CheckoutPage::class)->name('checkout');
    Route::get('/account', App\Livewire\Account\AccountPage::class)->name('account');
    Route::get('/account/orders', App\Livewire\Account\OrdersPage::class)->name('account.orders');
    Route::get('/account/orders/{id}', App\Livewire\Account\OrderDetailPage::class)->name('account.orders.show');
    Route::get('/account/orders/{id}/tracking', App\Livewire\Account\OrderTrackingPage::class)->name('account.orders.tracking');
    Route::get('/account/addresses', App\Livewire\Account\AddressesPage::class)->name('account.addresses');
    Route::get('/account/wishlist', App\Livewire\Account\WishlistPage::class)->name('account.wishlist');
});

// Admin Auth
Route::get('/admin/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// Admin Panel (auth + admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::get('/products', [ProductManageController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductManageController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductManageController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductManageController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductManageController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductManageController::class, 'destroy'])->name('products.destroy');

    // Categories
    Route::get('/categories', [CategoryManageController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryManageController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryManageController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryManageController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryManageController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryManageController::class, 'destroy'])->name('categories.destroy');

    // Banners
    Route::get('/banners', [BannerManageController::class, 'index'])->name('banners.index');
    Route::get('/banners/create', [BannerManageController::class, 'create'])->name('banners.create');
    Route::post('/banners', [BannerManageController::class, 'store'])->name('banners.store');
    Route::get('/banners/{banner}/edit', [BannerManageController::class, 'edit'])->name('banners.edit');
    Route::put('/banners/{banner}', [BannerManageController::class, 'update'])->name('banners.update');
    Route::delete('/banners/{banner}', [BannerManageController::class, 'destroy'])->name('banners.destroy');

    // Orders
    Route::get('/orders', [OrderManageController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderManageController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [OrderManageController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::patch('/orders/{order}/cancel', [OrderManageController::class, 'cancel'])->name('orders.cancel');
    Route::patch('/orders/{order}/verify-slip', [OrderManageController::class, 'verifySlip'])->name('orders.verifySlip');
    Route::patch('/orders/{order}/reject-slip', [OrderManageController::class, 'rejectSlip'])->name('orders.rejectSlip');
    Route::get('/orders/{order}/receipt', [OrderManageController::class, 'receipt'])->name('orders.receipt');
    Route::get('/orders/{order}/shipping-label', [OrderManageController::class, 'shippingLabel'])->name('orders.shippingLabel');

    // Customers
    Route::get('/customers', [CustomerManageController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [CustomerManageController::class, 'show'])->name('customers.show');

    // Stock Management
    Route::get('/stock', [StockManageController::class, 'index'])->name('stock.index');
    Route::get('/stock/history', [StockManageController::class, 'history'])->name('stock.history');
    Route::get('/stock/history/export', [StockManageController::class, 'exportHistory'])->name('stock.history.export');
    Route::get('/stock/bulk', [StockManageController::class, 'bulkForm'])->name('stock.bulk');
    Route::post('/stock/bulk', [StockManageController::class, 'bulkUpdate'])->name('stock.bulk.update');
    Route::get('/stock/export', [StockManageController::class, 'export'])->name('stock.export');
    Route::get('/stock/{product}', [StockManageController::class, 'show'])->name('stock.show');
    Route::post('/stock/{product}/adjust', [StockManageController::class, 'adjust'])->name('stock.adjust');

    // Shipping Rates
    Route::get('/shipping', [ShippingRateCtrl::class, 'index'])->name('shipping.index');
    Route::put('/shipping', [ShippingRateCtrl::class, 'update'])->name('shipping.update');

    // Reviews
    Route::get('/reviews', [ReviewManageController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewManageController::class, 'destroy'])->name('reviews.destroy');

    // Reports
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales/pdf', [ReportController::class, 'exportSalesPdf'])->name('reports.sales.pdf');
    Route::get('/reports/sales/excel', [ReportController::class, 'exportSalesExcel'])->name('reports.sales.excel');
    Route::get('/reports/best-selling', [ReportController::class, 'bestSelling'])->name('reports.best-selling');
    Route::get('/reports/best-selling/pdf', [ReportController::class, 'exportBestSellingPdf'])->name('reports.best-selling.pdf');
    Route::get('/reports/best-selling/excel', [ReportController::class, 'exportBestSellingExcel'])->name('reports.best-selling.excel');
    Route::get('/reports/low-stock', [ReportController::class, 'lowStock'])->name('reports.low-stock');
    Route::get('/reports/low-stock/pdf', [ReportController::class, 'exportLowStockPdf'])->name('reports.low-stock.pdf');
    Route::get('/reports/low-stock/excel', [ReportController::class, 'exportLowStockExcel'])->name('reports.low-stock.excel');

    // Contact Messages
    Route::get('/contact-messages', [ContactManageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{contactMessage}', [ContactManageController::class, 'show'])->name('contact-messages.show');
    Route::patch('/contact-messages/{contactMessage}/reply', [ContactManageController::class, 'reply'])->name('contact-messages.reply');
    Route::patch('/contact-messages/{contactMessage}/close', [ContactManageController::class, 'close'])->name('contact-messages.close');
    Route::delete('/contact-messages/{contactMessage}', [ContactManageController::class, 'destroy'])->name('contact-messages.destroy');

    // Coupons
    Route::get('/coupons', [CouponManageController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [CouponManageController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [CouponManageController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [CouponManageController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [CouponManageController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [CouponManageController::class, 'destroy'])->name('coupons.destroy');
});
