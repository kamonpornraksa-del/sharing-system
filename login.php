<?php
/**
 * login.php
 * หน้าเข้าสู่ระบบ
 */
session_start();
require_once __DIR__ . '/config/database.php';

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$errors = [];
$oldUsername = '';

$successMessage = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $oldUsername = $username;

    if ($username === '' || $password === '') {
        $errors[] = 'กรุณากรอก Username และ Password ให้ครบถ้วน';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        // ---------- ตรวจสอบรหัสผ่านด้วย password_verify() ----------
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role']     = $user['role'];

            if ($user['role'] === 'admin') {
                header('Location: admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit;
        } else {
            $errors[] = 'Username หรือ Password ไม่ถูกต้อง';
        }
    }
}

$pageTitle = 'เข้าสู่ระบบ';
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
    <div class="col-md-6 col-lg-5">
      <div class="form-card">
        <h3 class="text-center text-brand mb-4">เข้าสู่ระบบ</h3>

        <?php if ($successMessage): ?>
          <div class="alert alert-success alert-auto-dismiss"><?php echo h($successMessage); ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-danger">
            <ul class="mb-0">
              <?php foreach ($errors as $err): ?>
                <li><?php echo h($err); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
          <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo h($oldUsername); ?>" required autofocus>
          </div>
          <div class="mb-4">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-brand w-100">เข้าสู่ระบบ</button>
        </form>

        <p class="text-center mt-3 mb-0">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
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