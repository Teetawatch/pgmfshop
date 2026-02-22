<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Coupon;
use App\Models\ShippingRate;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin PGMF',
            'email' => 'admin@pgmfshop.com',
            'password' => Hash::make('password'),
            'phone' => '081-234-5678',
            'role' => 'admin',
            'email_verified_at' => now(),
            'addresses' => [
                [
                    'name' => 'Admin PGMF',
                    'phone' => '081-234-5678',
                    'address' => '123 ถ.สุขุมวิท',
                    'district' => 'วัฒนา',
                    'province' => 'กรุงเทพมหานคร',
                    'postalCode' => '10110',
                    'isDefault' => true,
                ],
            ],
        ]);

        // Customer user
        User::create([
            'name' => 'สมชาย ทดสอบ',
            'email' => 'customer@pgmfshop.com',
            'password' => Hash::make('password'),
            'phone' => '089-876-5432',
            'role' => 'customer',
            'email_verified_at' => now(),
            'addresses' => [
                [
                    'name' => 'สมชาย ทดสอบ',
                    'phone' => '089-876-5432',
                    'address' => '456 ถ.พหลโยธิน',
                    'district' => 'จตุจักร',
                    'province' => 'กรุงเทพมหานคร',
                    'postalCode' => '10900',
                    'isDefault' => true,
                ],
            ],
        ]);

        // Categories
        $categories = [
            ['name' => 'เสื้อผ้าผู้ชาย', 'slug' => 'mens-clothing', 'description' => 'เสื้อผ้าแฟชั่นผู้ชาย', 'sort_order' => 1],
            ['name' => 'เสื้อผ้าผู้หญิง', 'slug' => 'womens-clothing', 'description' => 'เสื้อผ้าแฟชั่นผู้หญิง', 'sort_order' => 2],
            ['name' => 'รองเท้า', 'slug' => 'shoes', 'description' => 'รองเท้าแฟชั่น รองเท้ากีฬา', 'sort_order' => 3],
            ['name' => 'กระเป๋า', 'slug' => 'bags', 'description' => 'กระเป๋าแฟชั่น กระเป๋าสะพาย', 'sort_order' => 4],
            ['name' => 'เครื่องประดับ', 'slug' => 'accessories', 'description' => 'เครื่องประดับ นาฬิกา แว่นตา', 'sort_order' => 5],
            ['name' => 'อิเล็กทรอนิกส์', 'slug' => 'electronics', 'description' => 'อุปกรณ์อิเล็กทรอนิกส์ แกดเจ็ต', 'sort_order' => 6],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Products
        $products = [
            [
                'category_id' => 1, 'name' => 'เสื้อยืดคอตตอนพรีเมียม', 'slug' => 'premium-cotton-tshirt',
                'description' => 'เสื้อยืดผ้าคอตตอน 100% เนื้อนุ่ม ใส่สบาย ระบายอากาศดี',
                'price' => 590, 'original_price' => 790, 'stock' => 150, 'sold' => 2345,
                'rating' => 4.8, 'review_count' => 128,
                'images' => ['https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600'],
                'tags' => ['เสื้อยืด', 'คอตตอน', 'ผู้ชาย'], 'is_featured' => true, 'is_new' => false,
            ],
            [
                'category_id' => 2, 'name' => 'เดรสผ้าลินินสไตล์มินิมอล', 'slug' => 'linen-minimal-dress',
                'description' => 'เดรสผ้าลินินแท้ สไตล์มินิมอล ใส่ได้ทุกโอกาส',
                'price' => 1290, 'original_price' => 1690, 'stock' => 80, 'sold' => 1890,
                'rating' => 4.7, 'review_count' => 95,
                'images' => ['https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600'],
                'tags' => ['เดรส', 'ลินิน', 'ผู้หญิง'], 'is_featured' => true, 'is_new' => false,
            ],
            [
                'category_id' => 3, 'name' => 'รองเท้าผ้าใบ Urban Runner', 'slug' => 'urban-runner-sneakers',
                'description' => 'รองเท้าผ้าใบดีไซน์ทันสมัย พื้นนุ่ม เบา ใส่สบาย',
                'price' => 1890, 'original_price' => 2490, 'stock' => 60, 'sold' => 1567,
                'rating' => 4.6, 'review_count' => 234,
                'images' => ['https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600'],
                'tags' => ['รองเท้า', 'ผ้าใบ', 'กีฬา'], 'is_featured' => true, 'is_new' => true,
            ],
            [
                'category_id' => 4, 'name' => 'กระเป๋าเป้ Travel Pro', 'slug' => 'travel-pro-backpack',
                'description' => 'กระเป๋าเป้สำหรับเดินทาง กันน้ำ มีช่องใส่โน้ตบุ๊ค',
                'price' => 1490, 'original_price' => 1990, 'stock' => 45, 'sold' => 890,
                'rating' => 4.5, 'review_count' => 67,
                'images' => ['https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600'],
                'tags' => ['กระเป๋า', 'เป้', 'เดินทาง'], 'is_featured' => false, 'is_new' => true,
            ],
            [
                'category_id' => 1, 'name' => 'กางเกงขายาว Slim Fit', 'slug' => 'slim-fit-pants',
                'description' => 'กางเกงขายาวทรง Slim Fit ผ้ายืดหยุ่น ใส่สบาย',
                'price' => 890, 'original_price' => 1290, 'stock' => 120, 'sold' => 1234,
                'rating' => 4.4, 'review_count' => 89,
                'images' => ['https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600'],
                'tags' => ['กางเกง', 'Slim Fit', 'ผู้ชาย'], 'is_featured' => true, 'is_new' => false,
            ],
            [
                'category_id' => 2, 'name' => 'เสื้อเชิ้ตผ้าไหม', 'slug' => 'silk-blouse',
                'description' => 'เสื้อเชิ้ตผ้าไหมเทียม เนื้อนุ่ม ลื่น ดูหรูหรา',
                'price' => 990, 'original_price' => null, 'stock' => 70, 'sold' => 567,
                'rating' => 4.3, 'review_count' => 45,
                'images' => ['https://images.unsplash.com/photo-1598554747436-c9293d6a588f?w=600'],
                'tags' => ['เสื้อเชิ้ต', 'ผ้าไหม', 'ผู้หญิง'], 'is_featured' => false, 'is_new' => true,
            ],
            [
                'category_id' => 6, 'name' => 'หูฟังไร้สาย Pro Max', 'slug' => 'wireless-earbuds-pro-max',
                'description' => 'หูฟังไร้สายระดับพรีเมียม เสียงดี ตัดเสียงรบกวน แบตอึด',
                'price' => 4990, 'original_price' => 6990, 'stock' => 100, 'sold' => 1567,
                'rating' => 4.9, 'review_count' => 567,
                'images' => ['https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600'],
                'tags' => ['หูฟัง', 'ไร้สาย', 'อิเล็กทรอนิกส์'], 'is_featured' => true, 'is_new' => true,
            ],
            [
                'category_id' => 5, 'name' => 'แว่นกันแดด Aviator Classic', 'slug' => 'aviator-classic-sunglasses',
                'description' => 'แว่นกันแดดทรง Aviator คลาสสิค เลนส์ UV400',
                'price' => 1590, 'original_price' => 2190, 'stock' => 55, 'sold' => 432,
                'rating' => 4.5, 'review_count' => 78,
                'images' => ['https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600'],
                'tags' => ['แว่นตา', 'กันแดด', 'เครื่องประดับ'], 'is_featured' => false, 'is_new' => false,
            ],
            [
                'category_id' => 3, 'name' => 'รองเท้าหนังออกงาน', 'slug' => 'formal-leather-shoes',
                'description' => 'รองเท้าหนังแท้สำหรับออกงาน ดีไซน์เรียบหรู',
                'price' => 2490, 'original_price' => 3290, 'stock' => 35, 'sold' => 345,
                'rating' => 4.6, 'review_count' => 56,
                'images' => ['https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=600'],
                'tags' => ['รองเท้า', 'หนัง', 'ออกงาน'], 'is_featured' => false, 'is_new' => false,
            ],
            [
                'category_id' => 6, 'name' => 'Smart Watch Series X', 'slug' => 'smart-watch-series-x',
                'description' => 'สมาร์ทวอทช์รุ่นใหม่ วัดชีพจร นับก้าว GPS กันน้ำ 50 เมตร',
                'price' => 5990, 'original_price' => 7990, 'stock' => 40, 'sold' => 890,
                'rating' => 4.7, 'review_count' => 345,
                'images' => ['https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600'],
                'tags' => ['สมาร์ทวอทช์', 'อิเล็กทรอนิกส์', 'กีฬา'], 'is_featured' => true, 'is_new' => true,
            ],
        ];

        foreach ($products as $prod) {
            Product::create($prod);
        }

        // Coupons
        Coupon::create([
            'code' => 'WELCOME10',
            'description' => 'ส่วนลด 10% สำหรับสมาชิกใหม่',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => 200,
            'min_purchase' => 500,
            'usage_limit' => 1000,
            'used_count' => 156,
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(3),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SAVE50',
            'description' => 'ลด 50 บาท เมื่อซื้อครบ 300 บาท',
            'discount_type' => 'fixed',
            'discount_value' => 50,
            'max_discount' => null,
            'min_purchase' => 300,
            'usage_limit' => 500,
            'used_count' => 89,
            'start_date' => now()->subWeek(),
            'end_date' => now()->addMonths(2),
            'is_active' => true,
        ]);

        Coupon::create([
            'code' => 'SUMMER25',
            'description' => 'ส่วนลด 25% โปรซัมเมอร์',
            'discount_type' => 'percentage',
            'discount_value' => 25,
            'max_discount' => 500,
            'min_purchase' => 1000,
            'usage_limit' => 200,
            'used_count' => 45,
            'start_date' => now(),
            'end_date' => now()->addMonths(1),
            'is_active' => true,
        ]);

        // Shipping Rates (ไปรษณีย์ไทย)
        ShippingRate::create([
            'name' => 'ค่าส่งพื้นฐาน (1-2 เล่ม)',
            'min_quantity' => 1,
            'max_quantity' => 2,
            'price' => 50,
            'is_active' => true,
            'sort_order' => 0,
        ]);

        ShippingRate::create([
            'name' => 'ค่าส่ง 3 เล่มขึ้นไป',
            'min_quantity' => 3,
            'max_quantity' => null,
            'price' => 80,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
