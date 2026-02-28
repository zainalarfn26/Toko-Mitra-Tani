# 🛒 Toko Mitra Tani - Sistem Kasir (POS System)

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel" alt="Laravel">
  <img src="https://img.shields.io/badge/PHP-8.1+-blue?style=for-the-badge&logo=php" alt="PHP">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-purple?style=for-the-badge&logo=bootstrap" alt="Bootstrap">
  <img src="https://img.shields.io/badge/MySQL-Database-orange?style=for-the-badge&logo=mysql" alt="MySQL">
</p>

Sistem Point of Sale (POS) modern untuk Toko Mitra Tani yang dibangun dengan Laravel 10. Aplikasi ini dirancang khusus untuk memudahkan pengelolaan transaksi penjualan, manajemen produk, dan pelaporan dengan antarmuka yang bersih dan responsif.

## ✨ Fitur Utama

### 👨‍💼 Dashboard Admin
- **Manajemen Pengguna**: Kelola user dengan role Admin dan Kasir
- **Manajemen Produk**: CRUD lengkap untuk produk dengan tracking stok
- **Laporan Transaksi**: View dan export laporan penjualan dengan filter tanggal
- **Dashboard Analytics**: Statistik penjualan dan overview bisnis

### 💰 Dashboard Kasir
- **Transaksi Real-time**: Sistem keranjang belanja berbasis AJAX
- **Pencarian Produk**: Search cepat berdasarkan nama atau kode produk
- **Validasi Pembayaran**: Validasi otomatis untuk pembayaran kurang/lebih
- **Cetak Struk**: Generate dan print struk transaksi
- **Manajemen Stok**: Auto-update stok setelah transaksi

### 🎨 User Interface
- Design modern dengan Argon Dashboard template
- Fully responsive untuk semua ukuran layar
- Icon Font Awesome 6.5.0
- Toast notification untuk feedback user
- Gradient theme dengan warna hijau sebagai primary color
- Custom favicon dan branding

## 🚀 Teknologi yang Digunakan

- **Backend**: Laravel 10.x
- **Frontend**: Bootstrap 5.3.3, Blade Templates
- **Database**: MySQL
- **JavaScript**: Vanilla JS dengan Fetch API
- **Icons**: Font Awesome 6.5.0
- **Authentication**: Laravel Breeze

## 📋 Requirements

- PHP >= 8.1
- Composer
- MySQL >= 5.7
- Node.js & NPM (untuk asset compilation)
- Web Server (Apache/Nginx)

## 🔧 Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/zainalarfn26/Toko-Mitra-Tani.git
cd Toko-Mitra-Tani
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan dengan database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_mitra_tani
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database & Seeder
```bash
php artisan migrate
php artisan db:seed
```

### 6. Compile Assets
```bash
npm run build
# atau untuk development
npm run dev
```

### 7. Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

## 👤 Default Login Credentials

### Admin
- **Email**: admin@mitratani.com
- **Password**: password

### Kasir
- **Email**: kasir@mitratani.com
- **Password**: password

## 📁 Struktur Database

### Users
- `id`, `name`, `email`, `password`, `role` (admin/kasir)
- Timestamps: `created_at`, `updated_at`

### Products
- `id`, `kode_product`, `name`, `price`, `stock`, `description`
- Timestamps: `created_at`, `updated_at`

### Transactions
- `id`, `transaction_code`, `user_id`, `total_amount`, `paid_amount`, `change_amount`
- Timestamps: `created_at`, `updated_at`

### Detail Transactions
- `id`, `transaction_id`, `product_id`, `quantity`, `price`, `subtotal`
- Timestamps: `created_at`, `updated_at`

## 🎯 Fitur Keamanan

- ✅ Authentication dengan Laravel Breeze
- ✅ Role-based Access Control (Admin & Kasir)
- ✅ CSRF Protection pada semua form
- ✅ Password hashing dengan bcrypt
- ✅ Middleware untuk proteksi route
- ✅ Input validation di backend dan frontend

## 📱 Screenshots

### Dashboard Kasir
Sistem transaksi real-time dengan keranjang belanja dinamis dan validasi pembayaran otomatis.

### Dashboard Admin  
Manajemen lengkap untuk produk, user, dan laporan penjualan dengan UI yang intuitif.

## 🛠️ Development

### Run Development Server
```bash
php artisan serve
npm run dev
```

### Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Generate New Migration
```bash
php artisan make:migration create_table_name
```

## 📝 Route List

### Guest Routes
- `/` - Landing page (redirect ke login)
- `/login` - Halaman login

### Admin Routes (Middleware: auth, role:admin)
- `/admin/dashboard` - Dashboard admin
- `/admin/users` - Manajemen user
- `/admin/produk` - Manajemen produk
- `/admin/laporan` - Laporan transaksi

### Kasir Routes (Middleware: auth, role:kasir)
- `/kasir/dashboard` - Dashboard kasir
- `/kasir/transaksi` - Halaman transaksi POS

## 🤝 Contributing

Kontribusi sangat welcome! Silakan fork repository ini dan submit pull request untuk perbaikan atau fitur baru.

1. Fork repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

Project ini adalah open-source dan tersedia di bawah [MIT License](LICENSE).

## 👨‍💻 Author

**Zainal Arifin**
- GitHub: [@zainalarfn26](https://github.com/zainalarfn26)
- Repository: [Toko-Mitra-Tani](https://github.com/zainalarfn26/Toko-Mitra-Tani)

## 🙏 Acknowledgments

- Laravel Framework
- Argon Dashboard Template
- Bootstrap 5
- Font Awesome Icons
- Semua kontributor open-source

---

<p align="center">Made with ❤️ for Toko Mitra Tani</p>

