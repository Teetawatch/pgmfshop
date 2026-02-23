<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockMovement;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Services\SlipVerifier;
use App\Mail\OrderConfirmationMail;
use App\Mail\PaymentSuccessMail;
use App\Livewire\Traits\WithSeo;

#[Layout('layouts.app')]
class CheckoutPage extends Component
{
    use WithSeo;
    use WithFileUploads;
    
    // List of all 77 Thai provinces
    private const THAI_PROVINCES = [
        'กรุงเทพมหานคร', 'สมุทรปราการ', 'นนทบุรี', 'ปทุมธานี', 'พระนครศรีอยุธยา', 'อ่างทอง', 
        'ลพบุรี', 'สิงห์บุรี', 'ชัยนาท', 'สระบุรี', 'ชัยภูมิ', 'นครราชสีมา', 'บุรีรัมย์', 
        'สุรินทร์', 'ศรีสะเกษ', 'อุบลราชธานี', 'ยโสธร', 'เลย', 'หนองคาย', 'หนองบัวลำภู', 
        'ขอนแก่น', 'อุดรธานี', 'มหาสารคาม', 'ร้อยเอ็ด', 'กาฬสินธุ์', 'สกลนคร', 'นครพนม', 
        'มุกดาหาร', 'เชียงใหม่', 'ลำปาง', 'ลำพูน', 'แพร่', 'น่าน', 'พะเยา', 'เชียงราย', 
        'แม่ฮ่องสอน', 'นครสวรรค์', 'อุทัยธานี', 'กำแพงเพชร', 'ตาก', 'สุโขทัย', 'พิษณุโลก', 
        'พิจิตร', 'เพชรบูรณ์', 'ราชบุรี', 'กาญจนบุรี', 'เพชรบุรี', 'ประจวบคีรีขันธ์', 'นครศรีธรรมราช', 
        'กระบี่', 'พังงา', 'ภูเก็ต', 'สุราษฎร์ธานี', 'ระนอง', 'ชุมพร', 'สงขลา', 'ตรัง', 
        'พัทลุง', 'ปัตตานี', 'ยะลา', 'นราธิวาส', 'บึงกาฬ'
    ];
    
    public $step = 1;
    public $paymentMethod = 'promptpay';
    public $orderId = '';
    public $finalTotal = 0;
    public $submitting = false;

    // Address form
    public $addressName = '';
    public $addressPhone = '';
    public $addressLine = '';
    public $addressDistrict = '';
    public $addressProvince = '';
    public $addressPostalCode = '';

    // Province autocomplete
    public $provinceQuery = '';
    public $showProvinceSuggestions = false;
    public $selectedProvinceIndex = -1;

    // Slip upload
    public $paymentSlip;
    public $slipPreview = '';
    public $slipError = '';

    // Transfer info
    public $transferDate = '';
    public $transferTime = '';
    public $transferAmount = '';

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $addresses = $user->addresses ?? [];
        $default = collect($addresses)->firstWhere('is_default', true) ?? collect($addresses)->first();

        if ($default) {
            $this->addressName = $default['name'] ?? $user->name;
            $this->addressPhone = $default['phone'] ?? $user->phone ?? '';
            $this->addressLine = $default['address'] ?? '';
            $this->addressDistrict = $default['district'] ?? '';
            $this->addressProvince = $default['province'] ?? '';
            $this->addressPostalCode = $default['postal_code'] ?? '';
            $this->provinceQuery = $this->addressProvince;
        } else {
            $this->addressName = $user->name;
            $this->addressPhone = $user->phone ?? '';
        }
    }

    public function goToStep2()
    {
        if (!$this->addressName || !$this->addressPhone || !$this->addressLine || !$this->addressProvince || !$this->addressPostalCode) {
            $this->dispatch('toast', message: 'กรุณากรอกที่อยู่จัดส่งให้ครบ', type: 'error');
            return;
        }
        $this->step = 2;
    }

    public function goToStep1()
    {
        $this->step = 1;
    }

    // Province autocomplete methods
    public function getProvinceSuggestionsProperty()
    {
        if (strlen($this->provinceQuery) < 1) {
            return [];
        }
        
        return collect(self::THAI_PROVINCES)
            ->filter(fn($province) => str_contains($province, $this->provinceQuery))
            ->take(10)
            ->values()
            ->toArray();
    }

    public function selectProvince($province)
    {
        $this->addressProvince = $province;
        $this->provinceQuery = $province;
        $this->showProvinceSuggestions = false;
        $this->selectedProvinceIndex = -1;
    }

    public function updatedProvinceQuery($value)
    {
        $this->addressProvince = $value;
        $this->showProvinceSuggestions = strlen($value) >= 1;
        $this->selectedProvinceIndex = -1;
    }

    public function closeProvinceSuggestions()
    {
        $this->showProvinceSuggestions = false;
        $this->selectedProvinceIndex = -1;
    }

    public function placeOrder()
    {
        if (!$this->addressName || !$this->addressPhone || !$this->addressLine || !$this->addressProvince || !$this->addressPostalCode) {
            $this->dispatch('toast', message: 'กรุณากรอกที่อยู่จัดส่งให้ครบ', type: 'error');
            $this->step = 1;
            return;
        }

        if (!$this->paymentSlip) {
            $this->dispatch('toast', message: 'กรุณาแนบสลิปการชำระเงิน', type: 'error');
            return;
        }

        // Validate slip file and transfer info
        $this->validate([
            'paymentSlip' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'transferDate' => 'required|date',
            'transferTime' => 'required|date_format:H:i',
            'transferAmount' => 'required|numeric|min:1',
        ], [
            'paymentSlip.required' => 'กรุณาแนบสลิปการชำระเงิน',
            'paymentSlip.image' => 'ไฟล์สลิปต้องเป็นรูปภาพเท่านั้น',
            'paymentSlip.mimes' => 'รองรับเฉพาะไฟล์ JPG, PNG, WEBP',
            'paymentSlip.max' => 'ขนาดไฟล์สลิปต้องไม่เกิน 5MB',
            'transferDate.required' => 'กรุณาระบุวันที่โอนเงิน',
            'transferDate.date' => 'รูปแบบวันที่ไม่ถูกต้อง',
            'transferTime.required' => 'กรุณาระบุเวลาที่โอน',
            'transferTime.date_format' => 'รูปแบบเวลาไม่ถูกต้อง',
            'transferAmount.required' => 'กรุณาระบุจำนวนเงินที่โอน',
            'transferAmount.numeric' => 'จำนวนเงินต้องเป็นตัวเลข',
            'transferAmount.min' => 'จำนวนเงินต้องมากกว่า 0',
        ]);

        // Run slip verification
        $slipPath = $this->paymentSlip->getRealPath();
        $cart = session('cart', []);
        $preProductIds = collect($cart)->pluck('product_id')->filter()->unique()->values()->toArray();
        if (empty($preProductIds)) $preProductIds = array_keys($cart);
        $preProducts = Product::whereIn('id', $preProductIds)->get()->keyBy('id');
        $preSubtotal = 0;
        foreach ($cart as $cartKey => $ci) {
            $pid = $ci['product_id'] ?? $cartKey;
            $pp = $preProducts->get($pid);
            if ($pp) $preSubtotal += $pp->price * $ci['quantity'];
        }
        $preTotalItems = collect($cart)->sum(fn($i) => $i['quantity']);
        $expectedTotal = $preSubtotal + ShippingRate::getCostForQuantity($preTotalItems);

        $verification = SlipVerifier::verify($slipPath, $expectedTotal);

        // Block if duplicate slip detected
        $hasDuplicate = collect($verification['checks'])->where('name', 'duplicate')->where('passed', false)->isNotEmpty();
        if ($hasDuplicate) {
            $this->dispatch('toast', message: 'สลิปนี้เคยถูกใช้แล้ว กรุณาอัปโหลดสลิปใหม่', type: 'error');
            return;
        }

        // Block if score too low (below 30%)
        if ($verification['percentage'] < 30) {
            $this->dispatch('toast', message: 'สลิปไม่ผ่านการตรวจสอบ กรุณาอัปโหลดสลิปที่ถูกต้อง', type: 'error');
            return;
        }

        $this->submitting = true;

        $cart = session('cart', []);
        $productIds = collect($cart)->pluck('product_id')->filter()->unique()->values()->toArray();
        if (empty($productIds)) $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $subtotal = 0;
        $orderItems = [];
        foreach ($cart as $cartKey => $item) {
            $productId = $item['product_id'] ?? $cartKey;
            $product = $products->get($productId);
            if ($product) {
                $lineTotal = $product->price * $item['quantity'];
                $subtotal += $lineTotal;
                $options = $item['options'] ?? [];
                $variantId = $item['variant_id'] ?? null;
                $orderItems[] = [
                    'product_id' => $product->id,
                    'variant_id' => $variantId,
                    'product_name' => $product->name,
                    'product_image' => is_array($product->images) ? ($product->images[0] ?? '') : '',
                    'price' => $product->price,
                    'quantity' => $item['quantity'],
                    'options' => !empty($options) ? $options : null,
                ];
            }
        }

        $totalQty = collect($cart)->sum(fn($i) => $i['quantity']);
        $shippingCost = ShippingRate::getCostForQuantity($totalQty);
        $total = $subtotal + $shippingCost;

        // Store slip directly in public/uploads for shared hosting compatibility
        $slipDir = public_path('uploads/slips');
        if (!file_exists($slipDir)) {
            mkdir($slipDir, 0755, true);
        }
        $slipFilename = 'slips/' . auth()->id() . '_' . time() . '.' . $this->paymentSlip->getClientOriginalExtension();
        $this->paymentSlip->storeAs('public', $slipFilename);
        // Also copy to public/uploads for shared hosting
        $sourcePath = storage_path('app/public/' . $slipFilename);
        $destPath = public_path('uploads/' . $slipFilename);
        if (file_exists($sourcePath)) {
            copy($sourcePath, $destPath);
        }

        // Auto-verify if score >= 80%
        $autoVerified = $verification['percentage'] >= 80;

        $order = Order::create([
            'user_id' => auth()->id(),
            'order_number' => Order::generateOrderNumber(),
            'status' => $autoVerified ? 'paid' : 'awaiting_payment',
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount' => 0,
            'total' => $total,
            'payment_method' => $this->paymentMethod,
            'payment_slip' => $slipFilename,
            'slip_verified' => $autoVerified,
            'slip_hash' => $verification['hash'] ?? null,
            'slip_verification_data' => $verification,
            'shipping_method' => 'thaipost',
            'payment_deadline' => now()->addHours(24),
            'transfer_date' => $this->transferDate,
            'transfer_time' => $this->transferTime,
            'transfer_amount' => $this->transferAmount,
            'shipping_address' => [
                'name' => $this->addressName,
                'phone' => $this->addressPhone,
                'address' => $this->addressLine,
                'district' => $this->addressDistrict,
                'province' => $this->addressProvince,
                'postal_code' => $this->addressPostalCode,
            ],
        ]);

        foreach ($orderItems as $oi) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $oi['product_id'],
                'variant_id' => $oi['variant_id'],
                'product_name' => $oi['product_name'],
                'product_image' => $oi['product_image'],
                'price' => $oi['price'],
                'quantity' => $oi['quantity'],
                'options' => $oi['options'],
                'total' => $oi['price'] * $oi['quantity'],
            ]);
        }

        // Decrease stock with audit trail
        // For variant items: deduct from variant (which auto-syncs product stock)
        // For non-variant items: deduct from product directly
        $productSoldQty = [];
        foreach ($cart as $cartKey => $item) {
            $pid = $item['product_id'] ?? $cartKey;
            $variantId = $item['variant_id'] ?? null;
            $qty = $item['quantity'];

            if ($variantId) {
                $variant = ProductVariant::find($variantId);
                if ($variant) {
                    $variant->adjustStock(
                        -$qty,
                        StockMovement::TYPE_OUT,
                        'ขายสินค้า',
                        StockMovement::REF_ORDER,
                        $order->order_number,
                        auth()->id()
                    );
                }
            } else {
                $p = Product::find($pid);
                if ($p) {
                    $p->adjustStock(
                        -$qty,
                        StockMovement::TYPE_OUT,
                        'ขายสินค้า',
                        StockMovement::REF_ORDER,
                        $order->order_number,
                        auth()->id()
                    );
                }
            }
            $productSoldQty[$pid] = ($productSoldQty[$pid] ?? 0) + $qty;
        }
        foreach ($productSoldQty as $pid => $totalSold) {
            Product::where('id', $pid)->increment('sold', $totalSold);
        }

        session(['cart' => []]);
        $this->orderId = $order->order_number;
        $this->finalTotal = $total;
        $this->step = 3;
        $this->submitting = false;
        session(['slipVerification' => $verification]);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'สั่งซื้อสำเร็จ!', type: 'success');

        $order->load(['items', 'user']);
        
        try {
            Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
        }

        if ($autoVerified) {
            try {
                Mail::to($order->user->email)->send(new PaymentSuccessMail($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send payment success email: ' . $e->getMessage());
            }
        }
    }

    private function calcShippingByQuantity(int $totalItems): float
    {
        return ShippingRate::getCostForQuantity($totalItems);
    }

    public function render()
    {
        $cart = session('cart', []);
        $productIds = collect($cart)->pluck('product_id')->filter()->unique()->values()->toArray();
        if (empty($productIds)) $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $subtotal = 0;
        $totalItems = 0;
        foreach ($cart as $cartKey => $item) {
            $productId = $item['product_id'] ?? $cartKey;
            $product = $products->get($productId);
            if ($product) {
                $items[] = ['product' => $product, 'quantity' => $item['quantity'], 'options' => $item['options'] ?? []];
                $subtotal += $product->price * $item['quantity'];
                $totalItems += $item['quantity'];
            }
        }

        $shippingCost = $this->calcShippingByQuantity($totalItems);
        $total = $subtotal + $shippingCost;
        $shippingRates = ShippingRate::getActiveRates();

        $this->setSeo(
            title: 'ชำระเงิน — PGMF Shop',
            description: 'ดำเนินการชำระเงินและสั่งซื้อสินค้าจาก PGMF Shop',
        );

        return $this->renderWithSeo('livewire.checkout-page', [
            'items' => $items,
            'subtotal' => $subtotal,
            'totalItems' => $totalItems,
            'shippingCost' => $shippingCost,
            'total' => $total,
            'shippingRates' => $shippingRates,
        ]);
    }
}
