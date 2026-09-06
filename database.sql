-- ==========================================================
-- ระบบแบ่งปันเสื้อผ้าและของใช้มือสอง (Sharing System)
-- ไฟล์ SQL สำหรับสร้างฐานข้อมูลและตารางทั้งหมด
-- วิธีใช้: เปิด phpMyAdmin -> แท็บ SQL -> วางไฟล์นี้ทั้งหมด -> กด Go
-- หรือรันผ่าน mysql command line: mysql -u root -p < database.sql
-- ==========================================================

-- สร้างฐานข้อมูล
CREATE DATABASE IF NOT EXISTS sharing_system
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE sharing_system;

-- ----------------------------------------------------------
-- ตาราง users : เก็บข้อมูลสมาชิกและผู้ดูแลระบบ
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    province VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    contact VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- ตาราง items : เก็บข้อมูลสิ่งของที่นำมาแบ่งปัน
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(200) NOT NULL,
    category ENUM('เสื้อผ้า', 'รองเท้า', 'กระเป๋า', 'ของใช้ในบ้าน', 'อุปกรณ์การเรียน', 'อื่น ๆ') NOT NULL,
    `condition` ENUM('ใหม่', 'สภาพดีมาก', 'สภาพดี', 'พอใช้ได้') NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    province VARCHAR(100) NOT NULL,
    district VARCHAR(100) NOT NULL,
    delivery_method VARCHAR(255) NOT NULL,
    contact VARCHAR(255) NOT NULL,
    status ENUM('available', 'processing', 'completed') NOT NULL DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_items_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- ตาราง requests : เก็บคำขอรับสิ่งของ
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    requester_id INT NOT NULL,
    message TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected', 'completed') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_requests_item FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    CONSTRAINT fk_requests_user FOREIGN KEY (requester_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- ตาราง reports : เก็บรายงานรายการที่ไม่เหมาะสม
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    reporter_id INT NOT NULL,
    reason VARCHAR(100) NOT NULL,
    detail TEXT DEFAULT NULL,
    status ENUM('pending', 'reviewed', 'resolved') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reports_item FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE,
    CONSTRAINT fk_reports_user FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- ข้อมูล Admin เริ่มต้น
-- Username: admin
-- Password: admin1234
-- (รหัสผ่านถูกเข้ารหัสด้วย password_hash() ของ PHP ล่วงหน้าแล้ว)
-- ----------------------------------------------------------
INSERT INTO users (fullname, username, password, phone, province, district, contact, role)
VALUES (
    'ผู้ดูแลระบบ',
    'admin',
    '$2b$12$yjsFTto9JOTn2cI2WjVA3O6dLax/YUTBiQvVZ6WTF50tfY1DlGIrK',
    '0800000000',
    'กรุงเทพมหานคร',
    'บางรัก',
    'admin@sharingsystem.local',
    'admin'
);