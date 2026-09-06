# ♻️ ระบบแบ่งปันเสื้อผ้าและของใช้มือสอง

**Clothing & Item Sharing System** - แพลตฟอร์มสำหรับแบ่งปันเสื้อผ้าและของใช้มือสองระหว่างสมาชิก

## 🎯 คุณสมบัติหลัก

### 👥 จัดการสมาชิก
- ✅ สมัครสมาชิก (Validation: Username ไม่ซ้ำ, Password ≥6 ตัวอักษร)
- ✅ เข้าสู่ระบบ (Password Hashing ด้วย `password_hash()`)
- ✅ ดูและแก้ไขโปรไฟล์ส่วนตัว
- ✅ เปลี่ยนรหัสผ่าน

### 📦 จัดการรายการสิ่งของ
- ✅ เพิ่มรายการใหม่ (ตรวจสอบ: ขนาด 5MB, JPG/PNG/GIF/WEBP)
- ✅ ดูรายการทั้งหมด (ค้นหา/กรอง/Pagination)
- ✅ แสดงรายละเอียดพร้อมรูปภาพ
- ✅ แก้ไขและลบรายการ
- ✅ สถานะสิ่งของ: พร้อมแบ่งปัน / กำลังดำเนินการ / ส่งมอบแล้ว

### 🤝 ระบบคำขอรับสิ่งของ
- ✅ ส่งคำขอรับ (พร้อมข้อความ)
- ✅ ดูสถานะคำขอของฉัน
- ✅ อนุมัติ/ปฏิเสธ/ส่งมอบแล้ว
- ✅ ระบบอัตโนมัติ: ตรวจสอบ Duplicate Request

### 🚩 ระบบรายงาน
- ✅ รายงานรายการที่ไม่เหมาะสม
- ✅ เลือกเหตุผล (ข้อมูลไม่ถูกต้อง, ละเมิดนโยบาย, ฯลฯ)
- ✅ เพิ่มรายละเอียดเพิ่มเติม

### 🛠️ แผงผู้ดูแลระบบ (Admin Panel)
- ✅ Dashboard: สรุปสถิติ (สมาชิก, รายการ, คำขอ, รายงาน)
- ✅ จัดการสมาชิก: ดู/ค้นหา/ลบ
- ✅ จัดการรายการสิ่งของ: ดู/เปลี่ยนสถานะ/ลบ
- ✅ จัดการรายงาน: ตรวจสอบ/เปลี่ยนสถานะ

---

## 🔒 ฟีเจอร์ด้านความปลอดภัย

✅ **Password Hashing** - ใช้ `password_hash()` + `password_verify()`  
✅ **XSS Protection** - ฟังก์ชัน `h()` ทุก Output  
✅ **SQL Injection Prevention** - Prepared Statements PDO  
✅ **Image Validation** - ตรวจสอบ MIME Type + File Size  
✅ **Session Security** - ตรวจสอบ `user_id` ทุกครั้ง  
✅ **CSRF Protection** - Session-based  

---

## 📋 ข้อกำหนดระบบ

- **PHP**: 7.4 หรือสูงกว่า
- **MySQL**: 5.7 หรือสูงกว่า
- **Web Server**: Apache / Nginx / PHP Built-in Server
- **Browser**: Modern browsers (Chrome, Firefox, Safari, Edge)

---

## 🚀 การติดตั้ง

### 1. Clone Repository
```bash
git clone https://github.com/kamonpornraksa-del/sharing-system.git
cd sharing-system
```

### 2. สร้างฐานข้อมูล
```bash
mysql -u root -p < database.sql
```

### 3. ตั้งค่า Config
แก้ไขไฟล์ `config/database.php`:
```php
$host = 'localhost';    // MySQL Host
$db = 'sharing_system'; // Database Name
$user = 'root';         // MySQL Username
$pass = '';             // MySQL Password
```

### 4. สร้างโฟลเดอร์ Uploads
```bash
mkdir -p uploads/items
chmod 755 uploads/items
```

### 5. รันระบบ

#### ใช้ PHP Built-in Server:
```bash
php -S localhost:8000
```

#### หรือใช้ XAMPP/WAMP/LAMP:
- Copy ไฟล์ไปที่ `htdocs` หรือ `www`
- เปิด `http://localhost/sharing-system`

---

## 🔐 ข้อมูล Login เริ่มต้น

| Username | Password  | Role  |
|----------|-----------|-------|
| admin    | admin1234 | Admin |

**หมายเหตุ**: เปลี่ยนรหัสผ่านหลังจากเข้าสู่ระบบครั้งแรก

---

## 📁 โครงสร้างไฟล์

```
sharing-system/
├── config/
│   └── database.php              # ฐานข้อมูล + Global Functions
├── admin/
│   ├── index.php                 # Dashboard
│   ├── users.php                 # จัดการสมาชิก
│   ├── items.php                 # จัดการรายการ
│   └── reports.php               # จัดการรายงาน
├── css/
│   └── style.css                 # Styling (Bootstrap 5)
├── js/
│   └── script.js                 # Client-side Scripts
├── uploads/
│   └── items/                    # เก็บรูปภาพ
├── database.sql                  # SQL Schema + Initial Data
├── index.php                     # หน้าแรก
├── register.php                  # สมัครสมาชิก
├── login.php                     # เข้าสู่ระบบ
├── logout.php                    # ออกจากระบบ
├── profile.php                   # โปรไฟล์
├── edit_profile.php              # แก้ไขโปรไฟล์
├── items.php                     # ดูรายการทั้งหมด
├── add_item.php                  # เพิ่มรายการ
├── edit_item.php                 # แก้ไขรายการ
├── delete_item.php               # ลบรายการ
├── item_detail.php               # รายละเอียด
├── my_items.php                  # รายการของฉัน
├── my_requests.php               # คำขอของฉัน
├── request_item.php              # ส่งคำขอ
├── report.php                    # รายงาน
├── .gitignore                    # Git ignore
└── README.md                     # Documentation
```

---

## 🎨 Stack Technology

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework**: Bootstrap 5.3.3
- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Security**: Password Hashing, Prepared Statements, XSS Protection

---

## 📖 วิธีการใช้งาน

### 1. สมัครสมาชิก
- คลิก "สมัครสมาชิก"
- กรอกข้อมูลทั้งหมด (Username ต้องไม่ซ้ำ)
- คลิก "สมัครสมาชิก"

### 2. เข้าสู่ระบบ
- กรอก Username และ Password
- คลิก "เข้าสู่ระบบ"

### 3. เพิ่มรายการ
- คลิก "+ เพิ่มรายการ"
- กรอกข้อมูล (ชื่อ, หมวดหมู่, สภาพ, รายละเอียด)
- อัปโหลดรูปภาพ (JPG/PNG/GIF/WEBP, ≤5MB)
- กรอก: จังหวัด, อำเภอ, วิธีรับของ, ช่องทางติดต่อ
- คลิก "บันทึกรายการ"

### 4. ขอรับสิ่งของ
- ดูรายการทั้งหมด → "ดูรายละเอียด"
- คลิก "ขอรับสิ่งของ"
- พิมพ์ข้อความ → คลิก "ส่งคำขอ"

### 5. จัดการคำขอ (เจ้าของรายการ)
- เข้า "รายการของฉัน"
- ดูคำขออื่นได้
- คลิก "อนุมัติ" / "ปฏิเสธ" / "ส่งมอบแล้ว"

### 6. รายงาน
- คลิก "🚩 รายงาน" ที่รายการ
- เลือกเหตุผล + เพิ่มรายละเอียด
- คลิก "ส่งรายงาน"

---

## 📞 ติดต่อ/ปัญหา

หากมีปัญหาหรือข้อเสนอแนะ โปรดสร้าง Issue หรือติดต่อผู้พัฒนา

---

## 📄 License

Project นี้ใช้ MIT License

---

## 🎓 ผู้พัฒนา

**kamonpornraksa-del** - Full Stack Developer

---

**ขอบคุณที่ใช้ระบบแบ่งปันของมือสองของเรา! ♻️**