-- H!MANJA Database - Fixed Version
-- Drop and recreate database

DROP DATABASE IF EXISTS himanja_db;
CREATE DATABASE himanja_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE himanja_db;

-- 1. Create himada table first
CREATE TABLE himada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    nama_lengkap VARCHAR(200) NOT NULL,
    daerah_asal VARCHAR(100) NOT NULL,
    warna_tema VARCHAR(7) NOT NULL DEFAULT '#b2e7e8',
    deskripsi TEXT,
    koordinat_lat DECIMAL(10, 8),
    koordinat_lng DECIMAL(11, 8),
    logo_url VARCHAR(255),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Create users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    kelas VARCHAR(20),
    nim VARCHAR(20),
    phone VARCHAR(20),
    role ENUM('super_admin', 'himada_admin', 'user') DEFAULT 'user',
    himada_id INT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE SET NULL,
    CONSTRAINT chk_email_stis CHECK (email LIKE '%@stis.ac.id' OR email = 'admin@stis.ac.id'),
    INDEX idx_users_email (email),
    INDEX idx_users_username (username),
    INDEX idx_users_role (role)
);

-- 3. Create products table
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    himada_id INT NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    kategori ENUM('makanan', 'merchandise', 'kaos', 'gantungan_kunci', 'oleh_oleh') NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(12, 2) NOT NULL,
    stok INT DEFAULT 0,
    stok_minimum INT DEFAULT 5,
    gambar_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_products_himada (himada_id),
    INDEX idx_products_available (is_available),
    INDEX idx_products_kategori (kategori)
);

-- 4. Create orders table
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    catatan_umum TEXT,
    shipping_address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_orders_user (user_id),
    INDEX idx_orders_status (status),
    INDEX idx_orders_number (order_number)
);

-- 5. Create order_items table
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    himada_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(12, 2) NOT NULL,
    subtotal DECIMAL(12, 2) GENERATED ALWAYS AS (quantity * price) STORED,
    catatan_produk TEXT,
    status ENUM('pending', 'confirmed', 'processing', 'ready', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_order_items_order (order_id),
    INDEX idx_order_items_himada (himada_id),
    INDEX idx_order_items_product (product_id)
);

-- 6. Create himart_posts table
CREATE TABLE himart_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    himada_id INT NOT NULL,
    judul VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar_url VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_himart_himada (himada_id),
    INDEX idx_himart_active (is_active)
);

-- 7. Create notifications table
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    himada_id INT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('order', 'stock', 'system', 'promotion') DEFAULT 'system',
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE SET NULL,
    INDEX idx_notifications_user (user_id),
    INDEX idx_notifications_read (is_read)
);

-- 8. Create activity_logs table
CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    action VARCHAR(50) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_activity_user (user_id),
    INDEX idx_activity_action (action),
    INDEX idx_activity_date (created_at)
);

-- Insert HIMADA data
INSERT INTO himada (nama, nama_lengkap, daerah_asal, warna_tema, deskripsi, koordinat_lat, koordinat_lng, contact_email, contact_phone) VALUES
('GIST', 'Gam Inong Statistik', 'Aceh', '#b2e7e8', 'Himpunan mahasiswa dari Serambi Mekah dengan kopi gayo dan mie aceh yang legendaris.', 4.6951, 96.7494, 'gist@stis.ac.id', '081234567890'),
('HIMARI', 'Himpunan Mahasiswa Riau dan Kepulauan Riau', 'Riau dan Kepulauan Riau', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Melayu dengan kekayaan budaya dan kuliner khas.', 0.2933, 101.7068, 'himari@stis.ac.id', '081234567891'),
('HIMAMIRA', 'Himpunan Mahasiswa Bumi Raflesia', 'Bengkulu', '#fbd2b6', 'Mahasiswa dari Bumi Raflesia dengan rendang bengkulu dan kue tat yang khas.', -3.7928, 102.2608, 'himamira@stis.ac.id', '081234567892'),
('IKMM', 'Ikatan Kekeluargaan Mahasiswa Minang', 'Sumatera Barat', '#fbd3df', 'Representasi mahasiswa Minang dengan rendang dan kebudayaan adat yang kuat.', -0.7893, 100.6543, 'ikmm@stis.ac.id', '081234567893'),
('IMASSU', 'Ikatan Mahasiswa Statistik Sumatera Utara', 'Sumatera Utara', '#b2e7e8', 'Mahasiswa dari tanah Batak dengan arsik dan saksang yang menggugah selera.', 3.5952, 98.6722, 'imassu@stis.ac.id', '081234567894'),
('KEMASS', 'Kerukunan Mahasiswa Statistik Sriwijaya', 'Sumatera Selatan', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Sriwijaya dengan pempek dan tekwan khasnya.', -2.9761, 104.7754, 'kemass@stis.ac.id', '081234567895'),
('KS3', 'Kekeluargaan Statistisi Serumpun Sebalai', 'Bangka Belitung', '#fbd2b6', 'Mahasiswa dari kepulauan timah dengan lempah kuning dan gangan yang lezat.', -2.7410, 106.4405, 'ks3@stis.ac.id', '081234567896'),
('SMS', 'Silaturahmi Mahasiswa Siginjai', 'Jambi', '#fbd3df', 'Representasi mahasiswa dari Bumi Serambi Mekah dengan gulai tempoyak dan dodol kentang.', -1.4851, 103.6044, 'sms@stis.ac.id', '081234567897'),
('SABURAI', 'Statistisi Sang Bumi Ruwai Jurai', 'Lampung', '#b2e7e8', 'Mahasiswa dari Bumi Ruwa Jurai dengan seruit dan kerupuk kemplang yang renyah.', -4.5585, 105.4068, 'saburai@stis.ac.id', '081234567898'),
('KAJABA', 'Kulawarga Jawa Barat Sareng Banten', 'Jawa Barat dan Banten', '#b0e8ce', 'Perwakilan mahasiswa Sunda dengan nasi liwet dan kerak telor yang autentik.', -6.9175, 107.6191, 'kajaba@stis.ac.id', '081234567899'),
('MAVIAS', 'Mahasiswa Batavia dan Sekitarnya', 'Jakarta, Depok, Tangerang, Bekasi', '#fbd2b6', 'Mahasiswa dari ibu kota dengan kerak telor dan soto betawi yang ikonik.', -6.2088, 106.8456, 'mavias@stis.ac.id', '081234567800'),
('JATENGSTIS', 'Himpunan Mahasiswa Daerah Jawa Tengah', 'Jawa Tengah', '#fbd3df', 'Representasi mahasiswa dari tanah Jawa dengan gudeg dan wingko babat yang manis.', -7.1509, 110.1403, 'jateng@stis.ac.id', '081234567801'),
('KBMSY', 'Keluarga Besar Mahasiswa STIS Yogyakarta', 'DI Yogyakarta', '#b2e7e8', 'Mahasiswa dari kota pelajar dengan gudeg dan bakpia pathok yang terkenal.', -7.7956, 110.3695, 'kbmsy@stis.ac.id', '081234567802'),
('BEKISAR', 'Himpunan Mahasiswa STIS Daerah Jawa Timur', 'Jawa Timur', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Surabaya dengan rawon dan rujak cingur yang pedas.', -7.2575, 112.7521, 'bekisar@stis.ac.id', '081234567803'),
('IMSAK', 'Ikatan Mahasiswa STIS Asal Kalimantan', 'Kalimantan', '#fbd2b6', 'Mahasiswa dari pulau Borneo dengan soto banjar dan amplang yang gurih.', -1.6815, 113.3824, 'imsak@stis.ac.id', '081234567804'),
('IMASSI', 'Ikatan Mahasiswa Statistik Sulawesi', 'Sulawesi', '#fbd3df', 'Representasi mahasiswa dari Sulawesi dengan coto makassar dan konro yang kaya rempah.', -2.5489, 120.1619, 'imassi@stis.ac.id', '081234567805'),
('BALISTIS', 'Himpunan Mahasiswa STIS Daerah Bali', 'Bali', '#b2e7e8', 'Mahasiswa dari Pulau Dewata dengan ayam betutu dan lawar yang eksotis.', -8.4095, 115.1889, 'balistis@stis.ac.id', '081234567806'),
('RINJANI', 'Himpunan Mahasiswa STIS Daerah Nusa Tenggara Barat', 'Nusa Tenggara Barat', '#b0e8ce', 'Perwakilan mahasiswa dari pulau seribu masjid dengan plecing kangkung dan ayam taliwang.', -8.6500, 117.3616, 'rinjani@stis.ac.id', '081234567807'),
('IMF', 'Ikatan Mahasiswa FLOBAMORA Politeknik Statistika STIS', 'Nusa Tenggara Timur dan Timor Leste', '#fbd2b6', 'Mahasiswa dari tanah Flores dengan jagung bose dan ikan asin yang khas.', -8.6573, 121.0794, 'imf@stis.ac.id', '081234567808'),
('MPC', 'Moluccas Papauan Community', 'Maluku dan Papua', '#fbd3df', 'Representasi mahasiswa dari tanah rempah dan cendrawasih dengan papeda dan ikan kuah kuning.', -4.2699, 138.0804, 'mpc@stis.ac.id', '081234567809');

-- Insert demo users with correct password hashing
INSERT INTO users (username, email, password, full_name, kelas, nim, role, himada_id, email_verified, is_active) VALUES
-- Super Admin
('admin', 'admin@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'ADMIN', NULL, 'super_admin', NULL, TRUE, TRUE),

-- HIMADA Admins (password: password)
('admin_gist', 'admin.gist@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin GIST', 'ADMIN', '222010001', 'himada_admin', 1, TRUE, TRUE),
('admin_himari', 'admin.himari@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin HIMARI', 'ADMIN', '222010002', 'himada_admin', 2, TRUE, TRUE),
('admin_himamira', 'admin.himamira@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin HIMAMIRA', 'ADMIN', '222010003', 'himada_admin', 3, TRUE, TRUE),

-- Regular Users (password: password)
('john_doe', 'john.doe@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '3SI1', '222110001', 'user', 1, TRUE, TRUE),
('jane_smith', 'jane.smith@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', '3SI2', '222110002', 'user', 2, TRUE, TRUE),
('ahmad_rizki', 'ahmad.rizki@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Rizki', '2ST1', '222110003', 'user', 3, TRUE, TRUE),
('siti_nurhaliza', 'siti.nurhaliza@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', '2ST2', '222110004', 'user', 4, TRUE, TRUE),
('mahasiswa', 'mahasiswa@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mahasiswa Test', '1SK1', '222110005', 'user', 1, TRUE, TRUE);

-- Insert sample products
INSERT INTO products (himada_id, nama_produk, kategori, deskripsi, harga, stok, stok_minimum, created_by) VALUES
-- GIST Products
(1, 'Kopi Gayo Arabica Premium', 'makanan', 'Kopi Gayo Arabica premium dari dataran tinggi Aceh dengan aroma yang harum dan cita rasa yang khas.', 55000, 30, 5, 2),
(1, 'Mie Aceh Instan', 'makanan', 'Mie Aceh dengan bumbu rempah yang pedas dan gurih, siap saji dalam kemasan praktis.', 18000, 50, 10, 2),
(1, 'Kaos GIST Official', 'kaos', 'Kaos resmi GIST dengan desain khas Aceh dan bahan cotton combed yang nyaman.', 75000, 25, 5, 2),

-- HIMARI Products  
(2, 'Kerupuk Sanjai Balado', 'makanan', 'Kerupuk sanjai khas Riau dengan bumbu balado pedas manis yang menggugah selera.', 25000, 40, 10, 3),
(2, 'Bolu Kemojo Riau', 'makanan', 'Bolu kemojo khas Riau dengan tekstur lembut dan rasa pandan yang harum.', 35000, 20, 5, 3),

-- HIMAMIRA Products
(3, 'Rendang Bengkulu', 'makanan', 'Rendang khas Bengkulu dengan bumbu rempah yang kaya dan daging yang empuk.', 45000, 15, 5, 4),
(3, 'Kue Tat Bengkulu', 'makanan', 'Kue tat khas Bengkulu dengan isian kelapa dan gula merah yang manis legit.', 28000, 30, 10, 4);

-- Insert sample HIMArt posts
INSERT INTO himart_posts (himada_id, judul, deskripsi, gambar_url, created_by) VALUES
(1, 'Festival Kuliner GIST 2024', 'Jangan lewatkan festival kuliner terbesar GIST dengan berbagai makanan khas Aceh!', '/assets/images/himart/festival-gist.jpg', 2),
(2, 'Pameran Budaya Riau', 'Menampilkan kekayaan budaya Melayu Riau dalam pameran spektakuler.', '/assets/images/himart/budaya-riau.jpg', 3),
(3, 'Workshop Masak Rendang', 'Belajar memasak rendang Bengkulu autentik langsung dari chef lokal!', '/assets/images/himart/workshop-rendang.jpg', 4);

-- Create indexes for better performance
CREATE INDEX idx_users_login ON users(email, password);
CREATE INDEX idx_products_search ON products(nama_produk, kategori);
CREATE INDEX idx_orders_date ON orders(created_at);
CREATE INDEX idx_notifications_unread ON notifications(user_id, is_read);

-- Show summary
SELECT 'Database setup completed successfully!' as status;
SELECT COUNT(*) as total_himada FROM himada;
SELECT COUNT(*) as total_users FROM users;
SELECT COUNT(*) as total_products FROM products;
