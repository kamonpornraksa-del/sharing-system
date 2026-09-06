<?php
/**
 * logout.php
 * ออกจากระบบ
 */
session_start();
session_destroy();
header('Location: login.php');
exit;