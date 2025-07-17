-- Database: himanja_db
-- Membuat database dan tabel untuk H!MANJA

CREATE DATABASE IF NOT EXISTS himanja_db;
USE himanja_db;

-- Tabel Users untuk sistem login
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    kelas VARCHAR(20) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel HIMADA (20 himpunan mahasiswa daerah)
CREATE TABLE himada (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(50) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    daerah_asal VARCHAR(100) NOT NULL,
    warna_tema VARCHAR(7) NOT NULL,
    deskripsi TEXT,
    koordinat_lat DECIMAL(10, 8),
    koordinat_lng DECIMAL(11, 8),
    logo_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Produk
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    himada_id INT NOT NULL,
    nama_produk VARCHAR(100) NOT NULL,
    kategori ENUM('makanan', 'merchandise', 'kaos', 'gantungan_kunci', 'oleh_oleh') NOT NULL,
    deskripsi TEXT NOT NULL,
    harga DECIMAL(10, 2) NOT NULL,
    stok INT DEFAULT 0,
    gambar_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE
);

-- Tabel Orders
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_number VARCHAR(20) UNIQUE NOT NULL,
    total_amount DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
    catatan_umum TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabel Order Items (detail pesanan per HIMADA)
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    himada_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    catatan_produk TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Tabel HIMArt Posts
CREATE TABLE himart_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    himada_id INT NOT NULL,
    judul VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar_url VARCHAR(255) NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (himada_id) REFERENCES himada(id) ON DELETE CASCADE
);

-- Update data HIMADA dengan informasi yang benar
DELETE FROM himada;

INSERT INTO himada (nama, nama_lengkap, daerah_asal, warna_tema, deskripsi, koordinat_lat, koordinat_lng) VALUES
('GIST', 'Gam Inong Statistik', 'Aceh', '#b2e7e8', 'Himpunan mahasiswa dari Serambi Mekah dengan kopi gayo dan mie aceh yang legendaris.', 4.6951, 96.7494),
('HIMARI', 'Himpunan Mahasiswa Riau dan Kepulauan Riau', 'Riau dan Kepulauan Riau', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Melayu dengan kekayaan budaya dan kuliner khas.', 0.2933, 101.7068),
('HIMAMIRA', 'Himpunan Mahasiswa Bumi Raflesia', 'Bengkulu', '#fbd2b6', 'Mahasiswa dari Bumi Raflesia dengan rendang bengkulu dan kue tat yang khas.', -3.7928, 102.2608),
('IKMM', 'Ikatan Kekeluargaan Mahasiswa Minang', 'Sumatera Barat', '#fbd3df', 'Representasi mahasiswa Minang dengan rendang dan kebudayaan adat yang kuat.', -0.7893, 100.6543),
('IMASSU', 'Ikatan Mahasiswa Statistik Sumatera Utara', 'Sumatera Utara', '#b2e7e8', 'Mahasiswa dari tanah Batak dengan arsik dan saksang yang menggugah selera.', 3.5952, 98.6722),
('KEMASS', 'Kerukunan Mahasiswa Statistik Sriwijaya', 'Sumatera Selatan', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Sriwijaya dengan pempek dan tekwan khasnya.', -2.9761, 104.7754),
('KS3', 'Kekeluargaan Statistisi Serumpun Sebalai', 'Bangka Belitung', '#fbd2b6', 'Mahasiswa dari kepulauan timah dengan lempah kuning dan gangan yang lezat.', -2.7410, 106.4405),
('SMS', 'Silaturahmi Mahasiswa Siginjai', 'Jambi', '#fbd3df', 'Representasi mahasiswa dari Bumi Serambi Mekah dengan gulai tempoyak dan dodol kentang.', -1.4851, 103.6044),
('SABURAI', 'Statistisi Sang Bumi Ruwai Jurai', 'Lampung', '#b2e7e8', 'Mahasiswa dari Bumi Ruwa Jurai dengan seruit dan kerupuk kemplang yang renyah.', -4.5585, 105.4068),
('KAJABA', 'Kulawarga Jawa Barat Sareng Banten', 'Jawa Barat dan Banten', '#b0e8ce', 'Perwakilan mahasiswa Sunda dengan nasi liwet dan kerak telor yang autentik.', -6.9175, 107.6191),
('MAVIAS', 'Mahasiswa Batavia dan Sekitarnya', 'Jakarta, Depok, Tangerang, Bekasi', '#fbd2b6', 'Mahasiswa dari ibu kota dengan kerak telor dan soto betawi yang ikonik.', -6.2088, 106.8456),
('JATENGSTIS', 'Himpunan Mahasiswa Daerah Jawa Tengah', 'Jawa Tengah', '#fbd3df', 'Representasi mahasiswa dari tanah Jawa dengan gudeg dan wingko babat yang manis.', -7.1509, 110.1403),
('KBMSY', 'Keluarga Besar Mahasiswa STIS Yogyakarta', 'DI Yogyakarta', '#b2e7e8', 'Mahasiswa dari kota pelajar dengan gudeg dan bakpia pathok yang terkenal.', -7.7956, 110.3695),
('BEKISAR', 'Himpunan Mahasiswa STIS Daerah Jawa Timur', 'Jawa Timur', '#b0e8ce', 'Perwakilan mahasiswa dari tanah Surabaya dengan rawon dan rujak cingur yang pedas.', -7.2575, 112.7521),
('IMSAK', 'Ikatan Mahasiswa STIS Asal Kalimantan', 'Kalimantan', '#fbd2b6', 'Mahasiswa dari pulau Borneo dengan soto banjar dan amplang yang gurih.', -1.6815, 113.3824),
('IMASSI', 'Ikatan Mahasiswa Statistik Sulawesi', 'Sulawesi', '#fbd3df', 'Representasi mahasiswa dari Sulawesi dengan coto makassar dan konro yang kaya rempah.', -2.5489, 120.1619),
('BALISTIS', 'Himpunan Mahasiswa STIS Daerah Bali', 'Bali', '#b2e7e8', 'Mahasiswa dari Pulau Dewata dengan ayam betutu dan lawar yang eksotis.', -8.4095, 115.1889),
('RINJANI', 'Himpunan Mahasiswa STIS Daerah Nusa Tenggara Barat', 'Nusa Tenggara Barat', '#b0e8ce', 'Perwakilan mahasiswa dari pulau seribu masjid dengan plecing kangkung dan ayam taliwang.', -8.6500, 117.3616),
('IMF', 'Ikatan Mahasiswa FLOBAMORA Politeknik Statistika STIS', 'Nusa Tenggara Timur dan Timor Leste', '#fbd2b6', 'Mahasiswa dari tanah Flores dengan jagung bose dan ikan asin yang khas.', -8.6573, 121.0794),
('MPC', 'Moluccas Papauan Community', 'Maluku dan Papua', '#fbd3df', 'Representasi mahasiswa dari tanah rempah dan cendrawasih dengan papeda dan ikan kuah kuning.', -4.2699, 138.0804);

-- Update sample products dengan HIMADA yang benar
DELETE FROM products;

INSERT INTO products (himada_id, nama_produk, kategori, deskripsi, harga, stok, gambar_url) VALUES
(1, 'Kopi Gayo Arabica Premium', 'makanan', 'Kopi Gayo Arabica premium dari dataran tinggi Aceh dengan aroma yang harum dan cita rasa yang khas.', 55000, 30, '/assets/images/products/kopi-gayo.jpg'),
(1, 'Mie Aceh Instan', 'makanan', 'Mie Aceh dengan bumbu rempah yang pedas dan gurih, siap saji dalam kemasan praktis.', 18000, 50, '/assets/images/products/mie-aceh.jpg'),
(1, 'Kaos GIST Official', 'kaos', 'Kaos resmi GIST dengan desain khas Aceh dan bahan cotton combed yang nyaman.', 75000, 25, '/assets/images/products/kaos-gist.jpg'),
(2, 'Kerupuk Sanjai Balado', 'makanan', 'Kerupuk sanjai khas Riau dengan bumbu balado pedas manis yang menggugah selera.', 25000, 40, '/assets/images/products/sanjai-balado.jpg'),
(2, 'Bolu Kemojo Riau', 'makanan', 'Bolu kemojo khas Riau dengan tekstur lembut dan rasa pandan yang harum.', 35000, 20, '/assets/images/products/bolu-kemojo.jpg'),
(3, 'Rendang Bengkulu', 'makanan', 'Rendang khas Bengkulu dengan bumbu rempah yang kaya dan daging yang empuk.', 45000, 15, '/assets/images/products/rendang-bengkulu.jpg'),
(3, 'Kue Tat Bengkulu', 'makanan', 'Kue tat khas Bengkulu dengan isian kelapa dan gula merah yang manis legit.', 28000, 30, '/assets/images/products/kue-tat.jpg'),
(4, 'Rendang Daging Sapi', 'makanan', 'Rendang daging sapi asli Minang dengan bumbu rempah tradisional yang autentik.', 65000, 20, '/assets/images/products/rendang-minang.jpg'),
(4, 'Keripik Balado', 'makanan', 'Keripik singkong dengan bumbu balado khas Minang yang pedas dan gurih.', 22000, 35, '/assets/images/products/keripik-balado.jpg'),
(4, 'Kaos Minang Heritage', 'kaos', 'Kaos dengan desain motif Minang dan tulisan aksara Arab Melayu.', 80000, 15, '/assets/images/products/kaos-minang.jpg'),
(5, 'Arsik Ikan Mas', 'makanan', 'Arsik ikan mas khas Batak dengan bumbu andaliman yang segar dan pedas.', 48000, 12, '/assets/images/products/arsik.jpg'),
(5, 'Saksang Babi', 'makanan', 'Saksang babi khas Batak dengan bumbu rempah yang kaya dan cita rasa yang unik.', 55000, 10, '/assets/images/products/saksang.jpg'),
(6, 'Pempek Palembang Frozen', 'makanan', 'Pempek asli Palembang dalam kemasan frozen, lengkap dengan kuah cuko pedas.', 45000, 25, '/assets/images/products/pempek.jpg'),
(6, 'Tekwan Palembang', 'makanan', 'Tekwan khas Palembang dengan kuah kaldu yang segar dan bakso ikan yang kenyal.', 32000, 20, '/assets/images/products/tekwan.jpg'),
(7, 'Lempah Kuning Bangka', 'makanan', 'Lempah kuning khas Bangka dengan ikan dan bumbu kuning yang harum.', 38000, 18, '/assets/images/products/lempah-kuning.jpg'),
(7, 'Gangan Ikan Patin', 'makanan', 'Gangan ikan patin khas Bangka dengan kuah asam pedas yang segar.', 42000, 15, '/assets/images/products/gangan-patin.jpg'),
(8, 'Gulai Tempoyak', 'makanan', 'Gulai tempoyak khas Jambi dengan durian fermentasi dan ikan patin.', 48000, 12, '/assets/images/products/gulai-tempoyak.jpg'),
(8, 'Dodol Kentang Kerinci', 'makanan', 'Dodol kentang khas Kerinci dengan tekstur kenyal dan rasa manis alami.', 25000, 25, '/assets/images/products/dodol-kentang.jpg'),
(9, 'Seruit Lampung', 'makanan', 'Seruit khas Lampung dengan sambal terasi dan lalapan segar yang pedas.', 28000, 22, '/assets/images/products/seruit.jpg'),
(9, 'Kerupuk Kemplang', 'makanan', 'Kerupuk kemplang khas Lampung dengan rasa ikan yang gurih dan tekstur renyah.', 22000, 35, '/assets/images/products/kemplang.jpg'),
(10, 'Nasi Liwet Sunda', 'makanan', 'Nasi liwet khas Sunda dengan lauk pauk lengkap dan sambal yang pedas.', 35000, 20, '/assets/images/products/nasi-liwet.jpg'),
(10, 'Kerak Telor Betawi', 'makanan', 'Kerak telor khas Betawi dengan telur bebek dan beras ketan yang gurih.', 25000, 30, '/assets/images/products/kerak-telor.jpg'),
(11, 'Soto Betawi', 'makanan', 'Soto Betawi dengan kuah santan yang kental dan daging sapi yang empuk.', 38000, 18, '/assets/images/products/soto-betawi.jpg'),
(11, 'Dodol Betawi', 'makanan', 'Dodol Betawi dengan rasa durian yang khas dan tekstur yang kenyal.', 32000, 25, '/assets/images/products/dodol-betawi.jpg'),
(12, 'Gudeg Yogya Kaleng', 'makanan', 'Gudeg khas Yogyakarta dalam kemasan kaleng, tahan lama dan praktis.', 32000, 25, '/assets/images/products/gudeg-kaleng.jpg'),
(12, 'Wingko Babat', 'makanan', 'Wingko babat khas Semarang dengan kelapa muda dan gula jawa yang manis.', 28000, 30, '/assets/images/products/wingko-babat.jpg'),
(13, 'Bakpia Pathok', 'makanan', 'Bakpia pathok khas Yogyakarta dengan isian kacang hijau yang lembut.', 35000, 20, '/assets/images/products/bakpia-pathok.jpg'),
(13, 'Gudeg Manggar', 'makanan', 'Gudeg manggar khas Yogyakarta dengan bunga kelapa yang unik dan lezat.', 38000, 15, '/assets/images/products/gudeg-manggar.jpg'),
(14, 'Rawon Surabaya', 'makanan', 'Rawon khas Surabaya dengan kuah hitam yang kaya rempah dan daging yang empuk.', 42000, 18, '/assets/images/products/rawon.jpg'),
(14, 'Rujak Cingur', 'makanan', 'Rujak cingur khas Surabaya dengan petis dan sayuran segar yang pedas.', 25000, 25, '/assets/images/products/rujak-cingur.jpg'),
(15, 'Soto Banjar', 'makanan', 'Soto Banjar khas Kalimantan dengan kuah kuning dan ayam kampung yang gurih.', 35000, 20, '/assets/images/products/soto-banjar.jpg'),
(15, 'Amplang Kalimantan', 'makanan', 'Amplang khas Kalimantan dengan ikan tenggiri yang gurih dan renyah.', 28000, 30, '/assets/images/products/amplang.jpg'),
(16, 'Coto Makassar', 'makanan', 'Coto Makassar dengan kuah kental dan jeroan sapi yang empuk dan gurih.', 45000, 15, '/assets/images/products/coto-makassar.jpg'),
(16, 'Konro Bakar', 'makanan', 'Konro bakar khas Makassar dengan bumbu rempah yang kaya dan daging yang empuk.', 55000, 12, '/assets/images/products/konro-bakar.jpg'),
(17, 'Ayam Betutu Bali', 'makanan', 'Ayam betutu khas Bali dengan bumbu base genep yang kaya rempah.', 65000, 10, '/assets/images/products/ayam-betutu.jpg'),
(17, 'Lawar Bali', 'makanan', 'Lawar khas Bali dengan sayuran segar dan bumbu yang pedas dan gurih.', 32000, 20, '/assets/images/products/lawar.jpg'),
(18, 'Plecing Kangkung', 'makanan', 'Plecing kangkung khas Lombok dengan sambal tomat yang pedas dan segar.', 25000, 25, '/assets/images/products/plecing-kangkung.jpg'),
(18, 'Ayam Taliwang', 'makanan', 'Ayam taliwang khas Lombok dengan bumbu pedas dan cara bakar yang khas.', 48000, 15, '/assets/images/products/ayam-taliwang.jpg'),
(19, 'Jagung Bose NTT', 'makanan', 'Jagung bose khas NTT dengan kuah kacang merah yang gurih dan mengenyangkan.', 28000, 22, '/assets/images/products/jagung-bose.jpg'),
(19, 'Ikan Asin Flores', 'makanan', 'Ikan asin khas Flores dengan cita rasa yang gurih dan tahan lama.', 35000, 18, '/assets/images/products/ikan-asin-flores.jpg'),
(20, 'Papeda Maluku', 'makanan', 'Papeda khas Maluku dengan kuah ikan kuning yang segar dan bergizi.', 32000, 20, '/assets/images/products/papeda.jpg'),
(20, 'Ikan Kuah Kuning', 'makanan', 'Ikan kuah kuning khas Maluku dengan bumbu rempah yang harum dan segar.', 45000, 15, '/assets/images/products/ikan-kuah-kuning.jpg');

-- Insert sample HIMArt posts
INSERT INTO himart_posts (himada_id, judul, deskripsi, gambar_url) VALUES
(1, 'Festival Kuliner GIST 2024', 'Jangan lewatkan festival kuliner terbesar GIST dengan berbagai makanan khas Tegal!', '/assets/images/himart/festival-gist.jpg'),
(2, 'Pameran Budaya Riau', 'Menampilkan kekayaan budaya Melayu Riau dalam pameran spektakuler.', '/assets/images/himart/budaya-riau.jpg'),
(3, 'Karapan Sapi Virtual HIMAMIRA', 'Saksikan pertunjukan karapan sapi virtual yang seru dan menegangkan!', '/assets/images/himart/karapan-sapi.jpg'),
(4, 'Expo Rempah Maluku', 'Pelajari sejarah dan kekayaan rempah-rempah Nusantara dari Maluku.', '/assets/images/himart/expo-rempah.jpg'),
(5, 'Workshop Masak Rica-Rica', 'Belajar memasak rica-rica autentik langsung dari chef Manado!', '/assets/images/himart/workshop-rica.jpg');

-- Insert sample users
INSERT INTO users (username, email, password, full_name, kelas, role) VALUES
('admin', 'admin@himanja.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'ADMIN', 'admin'),
('john_doe', 'john@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '3SI1', 'user'),
('jane_smith', 'jane@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jane Smith', '3SI2', 'user'),
('ahmad_rizki', 'ahmad@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ahmad Rizki', '2ST1', 'user'),
('siti_nurhaliza', 'siti@stis.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', '2ST2', 'user');

-- Insert sample orders
INSERT INTO orders (user_id, order_number, total_amount, status, catatan_umum) VALUES
(2, 'HMJ001', 75000, 'completed', 'Pesanan untuk acara kelas'),
(3, 'HMJ002', 125000, 'processing', 'Tolong dikemas rapi ya'),
(4, 'HMJ003', 89000, 'pending', 'Pesanan untuk oleh-oleh keluarga');

-- Insert sample order items
INSERT INTO order_items (order_id, himada_id, product_id, quantity, price, catatan_produk) VALUES
(1, 1, 1, 3, 15000, 'Tolong yang manis ya'),
(1, 1, 2, 1, 75000, 'Size L'),
(2, 2, 3, 2, 25000, 'Level pedas sedang'),
(2, 3, 4, 1, 35000, 'Matang sempurna'),
(3, 4, 5, 2, 12000, 'Warna random');
