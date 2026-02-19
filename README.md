# PGMF Shop

ระบบร้านค้าออนไลน์ มูลนิธิคณะก้าวหน้า (Progressive Movement Foundation)

## Tech Stack

- **Backend:** Laravel 12 + PHP 8.2+
- **Frontend:** Livewire v4 + Alpine.js + Tailwind CSS v4
- **Database:** MySQL 5.7+ / MariaDB 10.3+
- **Assets:** Vite (pre-built สำหรับ production)

## ความต้องการของระบบ (Requirements)

- PHP >= 8.2 พร้อม extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, GD/Imagick
- MySQL 5.7+ หรือ MariaDB 10.3+
- Composer 2.x
- mod_rewrite (Apache)

---

## การติดตั้งบน Shared Hosting

### วิธีที่ 1: Document Root ชี้ไปที่ public/ (แนะนำ)

หาก Hosting รองรับการเปลี่ยน Document Root (เช่น cPanel > Domains > Document Root):

1. **อัปโหลดไฟล์ทั้งหมด** ใน `backend/` ไปยัง root ของ hosting (เช่น `/home/username/pgmfshop/`)
2. **ตั้ง Document Root** ให้ชี้ไปที่ `/home/username/pgmfshop/public/`
3. ดำเนินการตามขั้นตอน **ตั้งค่าหลังอัปโหลด** ด้านล่าง

### วิธีที่ 2: อัปโหลดทั้งหมดไปที่ public_html

หาก Hosting ไม่รองรับการเปลี่ยน Document Root:

1. **อัปโหลดไฟล์ทั้งหมด** ใน `backend/` ไปยัง `/home/username/pgmfshop/` (โฟลเดอร์นอก public_html)
2. **คัดลอกไฟล์ใน `public/`** ไปยัง `public_html/`
3. **แก้ไข `public_html/index.php`** ให้ชี้ path ไปยังตำแหน่งที่ถูกต้อง:

```php
// แก้บรรทัดเหล่านี้:
require __DIR__.'/../pgmfshop/vendor/autoload.php';
$app = require_once __DIR__.'/../pgmfshop/bootstrap/app.php';

// และบรรทัด maintenance:
if (file_exists($maintenance = __DIR__.'/../pgmfshop/storage/framework/maintenance.php')) {
```

4. ดำเนินการตามขั้นตอน **ตั้งค่าหลังอัปโหลด** ด้านล่าง

### วิธีที่ 3: ใช้ .htaccess redirect (ง่ายที่สุด)

1. **อัปโหลดไฟล์ทั้งหมด** ใน `backend/` ไปยัง `public_html/` โดยตรง
2. ไฟล์ `.htaccess` ที่ root จะ redirect ไปยัง `public/` อัตโนมัติ
3. ดำเนินการตามขั้นตอน **ตั้งค่าหลังอัปโหลด** ด้านล่าง

---

## ตั้งค่าหลังอัปโหลด

### 1. ติดตั้ง Dependencies

```bash
cd /path/to/project
composer install --optimize-autoloader --no-dev
```

### 2. ตั้งค่า Environment

```bash
cp .env.example .env
php artisan key:generate
```

แก้ไขไฟล์ `.env`:

```env
APP_NAME="PGMF Shop"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_HOST=localhost
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### 3. ตั้งค่า Database

```bash
php artisan migrate --force
php artisan db:seed --force    # (ครั้งแรกเท่านั้น — สร้าง admin user)
```

### 4. สร้าง Storage Link

```bash
php artisan storage:link
```

> หาก `storage:link` ไม่ทำงานบน shared hosting ให้สร้าง symlink เอง:
> ```bash
> ln -s /path/to/project/storage/app/public /path/to/public/storage
> ```

### 5. ตั้งค่า Permissions

```bash
chmod -R 775 storage bootstrap/cache
```

### 6. Optimize สำหรับ Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan icons:cache       # (ถ้ามี)
```

---

## บัญชีเริ่มต้น (Default Accounts)

หลังจาก `db:seed`:

| Role     | Email                  | Password |
|----------|------------------------|----------|
| Admin    | admin@pgmfshop.com     | password |
| Customer | customer@pgmfshop.com  | password |

> **สำคัญ:** เปลี่ยนรหัสผ่านทันทีหลังจาก deploy!

---

## การพัฒนา (Development)

```bash
# ติดตั้ง dependencies
composer install
npm install

# รัน dev server
php artisan serve
npm run dev

# Build assets สำหรับ production
npm run build
```

---

## โครงสร้างโปรเจค

```
backend/
├── app/                    # Application code
│   ├── Http/Controllers/   # Controllers (Admin + API)
│   ├── Livewire/           # Livewire components (Storefront)
│   ├── Models/             # Eloquent models
│   └── ...
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── public/                 # Web root (Document Root)
│   └── build/              # Compiled assets (CSS/JS)
├── resources/
│   ├── css/                # Source CSS
│   ├── js/                 # Source JS
│   └── views/              # Blade templates
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
├── storage/                # Logs, cache, uploads
├── .env.example            # Environment template
├── .htaccess               # Root redirect to public/
├── composer.json           # PHP dependencies
└── package.json            # Node.js dependencies
```

---

## คุณสมบัติหลัก (Features)

- **หน้าร้าน (Storefront):** หน้าแรก, สินค้า (หนังสือ/เสื้อผ้า/อื่นๆ), ตะกร้า, ชำระเงิน
- **ระบบสมาชิก:** สมัคร, เข้าสู่ระบบ, Google/Facebook OAuth, ยืนยันอีเมล
- **รายการโปรด (Wishlist):** เพิ่ม/ลบสินค้าที่ชอบ
- **คำสั่งซื้อ:** ติดตามสถานะ, ติดตามพัสดุ (ไปรษณีย์ไทย)
- **ชำระเงิน:** โอนผ่าน PromptPay QR Code
- **คูปอง:** ระบบส่วนลด
- **รีวิว:** ให้คะแนนและรีวิวสินค้า
- **Admin Panel:** จัดการสินค้า, คำสั่งซื้อ, สต็อก, ลูกค้า, แบนเนอร์, คูปอง, ค่าจัดส่ง, รายงาน
- **อีเมลแจ้งเตือน:** ยืนยันคำสั่งซื้อ, แจ้งชำระเงิน, แจ้งจัดส่ง
- **SEO:** Meta tags, Open Graph, JSON-LD structured data, Breadcrumbs
