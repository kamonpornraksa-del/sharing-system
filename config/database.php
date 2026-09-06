<?php
/**
 * config/database.php
 * ฐานข้อมูล และฟังก์ชัน Global Helper
 */

$host = 'localhost';
$db = 'sharing_system';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('❌ Database connection error: ' . $e->getMessage());
}

// ---------- XSS Protection ----------
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// ---------- ตรวจสอบ Login ----------
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['success_message'] = 'กรุณาเข้าสู่ระบบ';
        header('Location: login.php');
        exit;
    }
}

// ---------- ตรวจสอบ Admin ----------
function require_admin() {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: ../index.php');
        exit;
    }
}

// ---------- Global Constants ----------
$GLOBALS['CATEGORIES'] = ['เสื้อผ้า', 'รองเท้า', 'กระเป๋า', 'ของใช้ในบ้าน', 'อุปกรณ์การเรียน', 'อื่น ๆ'];
$GLOBALS['CONDITIONS'] = ['ใหม่', 'สภาพดีมาก', 'สภาพดี', 'พอใช้ได้'];

$GLOBALS['ITEM_STATUS'] = [
    'available' => ['label' => '✓ พร้อมแบ่งปัน', 'color' => 'success'],
    'processing' => ['label' => '⏳ กำลังดำเนินการ', 'color' => 'warning'],
    'completed' => ['label' => '✅ ส่งมอบแล้ว', 'color' => 'secondary'],
];

$GLOBALS['REQUEST_STATUS'] = [
    'pending' => ['label' => 'รอตรวจสอบ', 'color' => 'warning'],
    'approved' => ['label' => 'อนุมัติแล้ว', 'color' => 'success'],
    'rejected' => ['label' => 'ปฏิเสธ', 'color' => 'danger'],
    'completed' => ['label' => 'ส่งมอบแล้ว', 'color' => 'secondary'],
];

$GLOBALS['REPORT_REASONS'] = ['ข้อมูลไม่ถูกต้อง', 'ละเมิดนโยบาย', 'ข้อมูลไม่เหมาะสม', 'เก่งห้ามแบ่งปัน', 'อื่น ๆ'];
$GLOBALS['REPORT_STATUS'] = [
    'pending' => ['label' => 'รอตรวจสอบ', 'color' => 'warning'],
    'reviewed' => ['label' => 'ตรวจสอบแล้ว', 'color' => 'info'],
    'resolved' => ['label' => 'แก้ไขแล้ว', 'color' => 'success'],
];

// ---------- Status Badge Helper ----------
function status_badge($statuses, $key) {
    if (!isset($statuses[$key])) return '';
    $s = $statuses[$key];
    return sprintf('<span class="badge bg-%s">%s</span>', $s['color'], h($s['label']));
}
?>