<?php
session_start();
require("DatabaseClass.php");//Bao gồm một tệp (có thể là định nghĩa lớp) có tên DatabaseClass.php có lẽ chứa các chức năng liên quan đến cơ sở dữ liệu.

# Kiểm tra và xác thực đầu vào
#Kiểm tra xem trường 'product_name' có được đặt trong dữ liệu POST không.
#Định nghĩa một hàm validate để làm sạch và xác thực dữ liệu người dùng.
#Xác thực tên sản phẩm và chuyển hướng với thông báo lỗi nếu trống.
if(isset($_POST['product_name']))  {
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $product_name = validate($_POST['product_name']);

    if(empty($product_name)) {
        // Chuyển hướng đến user_index.php với thông báo lỗi nếu tên sản phẩm trống
        header("Location: user_index.php?id=".$_SESSION['id']."?error=Product name is empty!");
        exit();
    }
#Truy vấn cơ sở dữ liệu
    else {
        $result = $db->getProductsByName($product_name);//Nếu tên sản phẩm không trống, nó thực hiện truy vấn cơ sở dữ liệu sử dụng phương thức getProductsByName từ DatabaseClass.
        
        if (mysqli_num_rows($result) > 0) {
            while ($records = mysqli_fetch_assoc($result)) {
                $queue[] = $records;
            }
            $_SESSION['query_temp_data'] = $queue;
            /*Chuyển hướng đến product.php với truy vấn tìm kiếm và dữ liệu đã lấy được*/
            header("Location: ../product.php?query=".$product_name);
            exit();
        }
        else {
            /*// Chuyển hướng đến product.php với truy vấn tìm kiếm và thông báo là không có kết quả*/
            header("Location: ../product.php?query=" . urlencode($product_name) . "&error=" . urlencode("none"));
            exit();
        }
    }
}

else {
    /*Chuyển hướng dự phòng*/
    header("Location: ../user_index.php?id=".$_SESSION['id']);
    exit();
}
?>