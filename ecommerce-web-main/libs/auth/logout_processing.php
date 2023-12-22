<?php
session_start ();
#Bắt đầu phiên làm việc (Session)
unset($_SESSION);
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"],$params["httponly"]);
}
#Kiểm tra và xóa cookie phiên làm việc (nếu có)
session_destroy();
#Hủy bỏ toàn bộ phiên làm việc:
header("Location: ../../index.php")
#Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
?>