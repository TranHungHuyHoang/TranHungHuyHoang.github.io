<?php
    $servername ="localhost";
    $user_name = "root";
    $password = "";

    $db_name = "electro";
    #Kiểm tra kết nối và xuất lỗi nếu có
    $conn = mysqli_connect($servername, $user_name, $password, $db_name);
    mysqli_set_charset($conn, 'UTF8');

    if (!$conn) {
        echo "Connection Failed.";
    }
    ?>
    #Kiểm tra xem kết nối đến cơ sở dữ liệu có thành công hay không. Nếu không, in ra thông báo lỗi.