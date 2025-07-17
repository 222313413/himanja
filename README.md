# H!MANJA - Himada Belanja

Platform jastip terpercaya untuk mahasiswa Politeknik Statistika STIS. Menghubungkan mahasiswa dengan produk khas daerah dari 20 HIMADA.

## 🚀 Fitur Utama

### Untuk Mahasiswa
- 🛍️ **Berbelanja Produk Khas**: Akses ke produk dari 20 HIMADA
- 🔍 **Live Search**: Pencarian real-time dengan AJAX
- 📱 **Responsive Design**: Optimal di semua device
- 🔐 **Login Aman**: Validasi email @stis.ac.id

### Untuk Admin HIMADA
- 📊 **Dashboard Lengkap**: Statistik penjualan dan stok
- 🛍️ **Kelola Produk**: CRUD produk dengan upload gambar
- 📦 **Manajemen Pesanan**: Proses pesanan dengan status tracking
- 📋 **Monitoring Stok**: Alert otomatis stok menipis
- 🎨 **HIMArt**: Kelola galeri dan postingan HIMADA
- 🔔 **Notifikasi Real-time**: Update pesanan dan stok

### Untuk Super Admin
- 👑 **Akses Penuh**: Kelola semua HIMADA dan data
- 📈 **Laporan Komprehensif**: Analytics penjualan global
- 👥 **Manajemen User**: Kelola admin dan mahasiswa
- ⚙️ **Konfigurasi Sistem**: Setting global aplikasi

## 🏗️ Teknologi

- **Backend**: PHP 7.4+, MySQL 8.0+
- **Frontend**: HTML5, CSS3, JavaScript ES6+
- **Framework CSS**: Custom CSS dengan Flexbox/Grid
- **Database**: MySQL dengan PDO
- **Security**: Password hashing, SQL injection protection
- **Real-time**: AJAX untuk live updates

## 📋 Persyaratan Sistem

- PHP 7.4 atau lebih tinggi
- MySQL 8.0 atau MariaDB 10.4+
- Web server (Apache/Nginx)
- Extensions: PDO, PDO_MySQL, mbstring, json

### Untuk Development
- XAMPP 8.0+ (Windows)
- LAMP Stack (Linux)
- MAMP (macOS)

## 🛠️ Instalasi

### 1. Clone/Download Project
\`\`\`bash
git clone https://github.com/username/himanja.git
# atau download ZIP dan extract ke htdocs/
\`\`\`

### 2. Setup Database
\`\`\`sql
-- Buat database
CREATE DATABASE himanja_db;

-- Import schema
mysql -u root -p himanja_db < database/himanja_db_updated.sql
\`\`\`

### 3. Konfigurasi Database
Edit `config/database.php`:
\`\`\`php
private $host = 'localhost';
private $db_name = 'himanja_db';
private $username = 'root';
private $password = 'your_password';
\`\`\`

### 4. Set Permissions
\`\`\`bash
chmod 755 config/
chmod 777 logs/
chmod 777 assets/images/
\`\`\`

### 5. Jalankan Setup
Buka browser dan akses: `http://localhost/himanja/setup.php`

## 👥 Akun Demo

### Super Admin
- **Email**: admin@stis.ac.id
- **Password**: password
- **Akses**: Semua fitur sistem

### Admin HIMADA (GIST)
- **Email**: admin.gist@stis.ac.id
- **Password**: password
- **Akses**: Kelola produk dan pesanan GIST

### Mahasiswa
- **Email**: john.doe@stis.ac.id
- **Password**: password
- **Akses**: Berbelanja produk

## 📁 Struktur Project

\`\`\`
himanja/
├── admin/                  # Dashboard admin
│   ├── himada-dashboard.php
│   ├── products.php
│   ├── orders.php
│   ├── stock.php
│   ├── himart.php
│   └── reports.php
├── assets/                 # Static files
│   ├── css/
│   │   ├── style.css
│   │   ├── admin.css
│   │   ├── auth.css
│   │   └── animations.css
│   ├── js/
│   │   ├── main.js
│   │   ├── admin.js
│   │   ├── auth.js
│   │   └── search.js
│   └── images/
│       ├── products/
│       └── himart/
├── config/                 # Konfigurasi
│   └── database.php
├── database/               # Database schema
│   ├── himanja_db_updated.sql
│   └── himanja_erd.sql
├── logs/                   # Log files
├── index.php              # Homepage
├── login.php              # Authentication
├── register.php           # Registration
├── products.php           # Katalog produk
├── order.php              # Pemesanan
├── himart.php             # Galeri HIMADA
├── search.php             # API search
├── logout.php             # Logout
├── setup.php              # Setup installer
└── README.md              # Dokumentasi
\`\`\`

## 🗄️ Database Schema

### Tabel Utama
- **users**: Data pengguna (mahasiswa, admin)
- **himada**: Data 20 HIMADA
- **products**: Produk yang dijual
- **orders**: Pesanan pelanggan
- **order_items**: Detail item pesanan
- **himart_posts**: Postingan galeri HIMADA
- **stock_history**: Riwayat perubahan stok
- **notifications**: Notifikasi sistem

### Relasi Penting
- User → HIMADA (admin relationship)
- HIMADA → Products (one-to-many)
- Orders → Order Items (one-to-many)
- Products → Stock History (one-to-many)

## 🔐 Sistem Keamanan

### Authentication
- Password hashing dengan `password_hash()`
- Session management yang aman
- Validasi email @stis.ac.id

### Authorization
- Role-based access control
- Admin HIMADA hanya akses data mereka
- Super admin akses penuh

### Data Protection
- SQL injection protection dengan PDO
- XSS protection dengan `htmlspecialchars()`
- CSRF protection untuk form

## 🎨 UI/UX Features

### Design System
- **Colors**: Soft pastel palette
- **Typography**: Poppins font family
- **Layout**: Flexbox dan CSS Grid
- **Animations**: CSS animations dan transitions

### Responsive Design
- Mobile-first approach
- Breakpoints: 480px, 768px, 1024px
- Touch-friendly interface

### Accessibility
- Semantic HTML
- ARIA labels
- Keyboard navigation
- Screen reader support

## 📊 Fitur Admin Dashboard

### Dashboard Overview
- Statistik real-time
- Grafik penjualan
- Alert stok menipis
- Pesanan terbaru

### Manajemen Produk
- CRUD produk lengkap
- Upload gambar
- Kategori produk
- Tracking stok otomatis

### Manajemen Pesanan
- Status tracking
- Update status pesanan
- Notifikasi otomatis
- Export laporan

### Analytics
- Laporan penjualan
- Analisis produk terlaris
- Statistik pelanggan
- Export data

## 🔔 Sistem Notifikasi

### Auto Notifications
- Pesanan baru → Admin HIMADA
- Stok menipis → Admin HIMADA
- Status pesanan → Pelanggan

### Real-time Updates
- AJAX polling setiap 30 detik
- Badge counter notifikasi
- Toast notifications

## 🛒 Fitur E-commerce

### Shopping Cart
- Add to cart functionality
- Quantity management
- Multi-HIMADA checkout

### Order Management
- Order tracking
- Status updates
- Order history

### Payment Integration
- Ready for payment gateway
- Order confirmation
- Receipt generation

## 🔍 Live Search

### Features
- Real-time search dengan AJAX
- Search suggestions
- Keyboard navigation
- Search analytics

### Search Scope
- Produk (nama, deskripsi, kategori)
- HIMADA (nama, daerah)
- HIMArt posts
- Kategori produk

## 📱 Mobile Optimization

### Responsive Features
- Mobile-friendly navigation
- Touch gestures
- Optimized images
- Fast loading

### PWA Ready
- Service worker ready
- Offline capability
- App-like experience

## 🚀 Performance

### Optimization
- Lazy loading images
- CSS/JS minification
- Database indexing
- Caching strategies

### Monitoring
- Error logging
- Performance metrics
- User analytics

## 🧪 Testing

### Manual Testing
- Cross-browser compatibility
- Mobile device testing
- User acceptance testing

### Security Testing
- SQL injection testing
- XSS vulnerability testing
- Authentication testing

## 📈 Future Enhancements

### Planned Features
- [ ] Payment gateway integration
- [ ] Push notifications
- [ ] Mobile app (React Native)
- [ ] Advanced analytics
- [ ] Multi-language support
- [ ] API for third-party integration

### Technical Improvements
- [ ] Redis caching
- [ ] CDN integration
- [ ] Docker containerization
- [ ] CI/CD pipeline
- [ ] Unit testing

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Developer

Developed with ❤️ for Politeknik Statistika STIS community.

## 📞 Support

Untuk bantuan teknis atau pertanyaan:
- Email: support@himanja.stis.ac.id
- GitHub Issues: [Create Issue](https://github.com/username/himanja/issues)

---

**H!MANJA** - Jajan Lokal, Rasa Nasional, Akses Digital! 🛍️
\`\`\`

Sekarang struktur project sudah lengkap dengan:

1. ✅ **File path yang benar** menggunakan `__DIR__`
2. ✅ **Struktur folder yang terorganisir**
3. ✅ **Setup script** untuk instalasi mudah
4. ✅ **CSS admin dashboard** yang responsive
5. ✅ **JavaScript admin** dengan fitur lengkap
6. ✅ **Dokumentasi README** yang komprehensif

**Untuk menjalankan project:**

1. **Extract semua file** ke folder `htdocs/himanja/`
2. **Jalankan XAMPP** dan start Apache + MySQL
3. **Buka browser** ke `http://localhost/himanja/setup.php`
4. **Ikuti instruksi setup** yang muncul
5. **Login dengan akun demo** yang tersedia

Project sekarang sudah siap digunakan dengan semua fitur admin HIMADA yang diminta! 🎉
