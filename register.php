<?php
/**
 * register.php
 * หน้าสมัครสมาชิก
 */
session_start();
require_once __DIR__ . '/config/database.php';

// ถ้า Login อยู่แล้ว ไม่ต้องสมัครซ้ำ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$old = [
    'fullname' => '', 'username' => '', 'phone' => '',
    'province' => '', 'district' => '', 'contact' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $district = trim($_POST['district'] ?? '');
    $contact  = trim($_POST['contact'] ?? '');

    $old = compact('fullname', 'username', 'phone', 'province', 'district', 'contact');

    // ---------- ตรวจสอบข้อมูล ----------
    if ($fullname === '') $errors[] = 'กรุณากรอกชื่อ-นามสกุล';
    if ($username === '') $errors[] = 'กรุณากรอก Username';
    if ($username !== '' && !preg_match('/^[a-zA-Z0-9_]{4,50}$/', $username)) {
        $errors[] = 'Username ต้องเป็นตัวอักษรภาษาอังกฤษ/ตัวเลข/ขีดล่าง ความยาว 4-50 ตัวอักษร';
    }
    if (strlen($password) < 6) $errors[] = 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร';
    if ($password !== $confirm) $errors[] = 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน';
    if ($phone === '' || !preg_match('/^[0-9\-]{9,15}$/', $phone)) $errors[] = 'กรุณากรอกเบอร์โทรศัพท์ให้ถูกต้อง';
    if ($province === '') $errors[] = 'กรุณากรอกจังหวัด';
    if ($district === '') $errors[] = 'กรุณากรอกอำเภอ';
    if ($contact === '') $errors[] = 'กรุณากรอกช่องทางติดต่อ';

    // ---------- ตรวจสอบ Username ซ้ำ ----------
    if (empty($errors)) {
        $checkStmt = $pdo->prepare("SELECT id FROM users WHERE username = :username");
        $checkStmt->execute([':username' => $username]);
        if ($checkStmt->fetch()) {
            $errors[] = 'Username นี้มีผู้ใช้งานแล้ว กรุณาเลือก Username อื่น';
        }
    }

    // ---------- บันทึกข้อมูล ----------
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $pdo->prepare(
            "INSERT INTO users (fullname, username, password, phone, province, district, contact, role)
             VALUES (:fullname, :username, :password, :phone, :province, :district, :contact, 'user')"
        );
        $insertStmt->execute([
            ':fullname' => $fullname,
            ':username' => $username,
            ':password' => $hashedPassword,
            ':phone'    => $phone,
            ':province' => $province,
            ':district' => $district,
            ':contact'  => $contact,
        ]);

        $_SESSION['success_message'] = 'สมัครสมาชิกสำเร็จ กรุณาเข้าสู่ระบบ';
        header('Location: login.php');
        exit;
    }
}

$pageTitle = 'สมัครสมาชิก';
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo h($pageTitle); ?> | ระบบแบ่งปันเสื้อผ้าและของใช้มือสอง</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-brand">
  <div class="container">
    <a class="navbar-brand" href="index.php">♻️ ระบบแบ่งปันของมือสอง</a>
  </div>
</nav>

<div class="container auth-wrap">
  <div class="row justify-content-center w-100">
    <div class="col-md-8 col-lg-6">
      <div class="form-card">
        <h3 class="text-center text-brand mb-4">สมัครสมาชิก</h3>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($errors as $err): ?>
                <li><?php echo h($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="POST" action="register.php" novalidate>
          <div class="mb-3">
            <label class="form-label">ชื่อ-นามสกุล</label>
            <input type="text" name="fullname" class="form-control" value="<?php echo h($old['fullname']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo h($old['username']); ?>" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">รหัสผ่าน</label>
              <input type="password" name="password" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">ยืนยันรหัสผ่าน</label>
              <input type="password" name="confirm_password" class="form-control" required>
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label">เบอร์โทรศัพท์</label>
            <input type="text" name="phone" class="form-control" value="<?php echo h($old['phone']); ?>" required>
          </div>
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">จังหวัด</label>
              <input type="text" name="province" class="form-control" value="<?php echo h($old['province']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">อำเภอ</label>
              <input type="text" name="district" class="form-control" value="<?php echo h($old['district']); ?>" required>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label">ช่องทางติดต่อ (เช่น Line ID, เบอร์โทร, Facebook)</label>
            <input type="text" name="contact" class="form-control" value="<?php echo h($old['contact']); ?>" required>
          </div>
          <button type="submit" class="btn btn-brand w-100">สมัครสมาชิก</button>
        </form>

        <p class="text-center mt-3 mb-0">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
      </div>
    </div>
  </div>
</div>

<footer class="site-footer">
  <div class="container text-center">
    <p class="mb-0">© <?php echo date('Y'); ?> ระบบแบ่งปันเสื้อผ้าและของใช้มือสอง</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>