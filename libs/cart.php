<?php
// kết nối đén file dữ liệu.
include "db_conn.php"; // Include your database connection file here
session_start();

// Khởi tạo biến phiên khi chưa được đặt
if (!isset($_SESSION['id'])) {
    $_SESSION['id'] = null;
}

if (!isset($_SESSION['shop_session'])) {
    $_SESSION['shop_session'] = null;
}

// Lấy thông tin người dùng.
$user_id = $_SESSION['id'];
$session_id = $_SESSION['shop_session'];
//Tạo câu truy vấn SQL để lấy thông tin sản phẩm trong giỏ hàng
$query = "SELECT * FROM cart_item c, product p WHERE c.product_id = p.id AND c.session_id = '$session_id'";
$result = mysqli_query($conn, $query);
$cart_items = array();
$total_amount = 0;
// Xử lý kết quả truy vấn
if (mysqli_num_rows($result) > 0) {
    while ($data = mysqli_fetch_assoc($result)) {
        $cart_items[] = $data;
        $total_amount += $data['price'] * $data['quantity'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../css/cart.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>

<body>

    <div class="container">
        <h2>Giỏ hàng của bạn</h2>
        <?php
        if (count($cart_items) > 0) {
            foreach ($cart_items as $item) { ?>
                <div class="cart-item" data-pid="<?php echo $item['product_id']; ?>">
                    <img src="<?php echo $item['photo']; ?>" alt="<?php echo $item['name']; ?>" />
                    <span class="item-name"><?php echo $item['name']; ?></span>
                    <span class="item-price">$<?php echo $item['price']; ?></span>
                    <input type="number" class="item-quantity" value="<?php echo isset($item['quantity']) ? $item['quantity'] : 1; ?>" data-pid="<?php echo $item['product_id']; ?>" />
                    <button class="remove-item" data-pprice="<?php echo $item['price']; ?>" data-psku="<?php echo $item['sku']; ?>" data-pid="<?php echo $item['product_id']; ?>">Remove</button>
                </div>
            <?php }
        } else { ?>
        <div class="cart-empty">
            <p>GIỎ HÀNG CỦA BẠN TRỐNG</p>
            <img src="../img/cart-empty.png" alt="">
        </div>
        <?php } ?>

        <div class="cart-total">
            <p>Total: $<?php echo $total_amount; ?></p>
        </div>
        <button class="checkout-button">Checkout</button>
    </div>

    <div class="checkout-modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Thanh toán thành công!</p>
            <!-- Bạn có thể tùy chỉnh nội dung của biểu mẫu này dựa trên yêu cầu của mình -->
            <form id="checkout-form">
                <!-- Thêm bất kỳ trường bổ sung nào bạn có thể cần trong biểu mẫu thanh toán của bạn -->
                <input type="submit" value="Đóng">
            </form>
        </div>
    </div>

    <script>
        // Hàm được thực thi khi trang đã được tải
        $(document).ready(function() {
            // Xử lý sự kiện khi số lượng sản phẩm thay đổi
            $(".item-quantity").change(function() {
                var productId = $(this).data("pid");
                var newQuantity = $(this).val();

                $.ajax({
                    type: "POST",
                    url: "update_quantity.php",
                    data: {
                        action: "update",
                        product_id: productId,
                        quantity: newQuantity
                    },
                    success: function(data) {
                        updateCartItemPrice(productId, newQuantity);
                        updateTotalAmount();
                    },
                    error: function() {
                        alert("Error updating quantity!");
                    }
                });
            });
 // Xử lý sự kiện khi người dùng muốn xóa sản phẩm
            $(".remove-item").click(function() {
                var productId = $(this).data("pid");
                var productPrice = $(this).data("pprice");
                var productSku = $(this).data("psku");

                $.ajax({
                    type: "POST",
                    url: "update_quantity.php",
                    data: {
                        action: "delete",
                        product_id: productId,
                        quantity: 0
                    },
                    success: function(data) {
                        $(".cart-item[data-pid='" + productId + "']").remove();
                        updateTotalAmount();
                    },
                    error: function() {
                        alert("Error removing item!");
                    }
                });
            });
 // Xử lý sự kiện khi người dùng nhấn nút thanh toán
            $(".checkout-button").click(function() {
                // Hiển thị modal thanh toán
                $(".checkout-modal").show();

                //nếu không có sản phẩm trong giỏ hàng thì không cho thanh toán
                if ($(".cart-item").length == 0) {
                    $(".checkout-modal p").text("Giỏ hàng của bạn đang trống!");
                    $("#checkout-form").hide();
                }

                // Tùy chọn, bạn cũng có thể gửi yêu cầu AJAX để xóa giỏ hàng ở phía máy chủ
                $.ajax({
                    type: "POST",
                    url: "clear_cart.php", // Tạo một tệp PHP để xử lý việc xóa giỏ hàng
                    success: function(data) {
                        // Xử lý thành công nếu cần
                    },
                    error: function() {
                        alert("Lỗi khi xóa giỏ hàng!");
                    }
                });

                // Tự động tải lại trang sau khi đóng modal
                $(".checkout-modal .close, #checkout-form").on("click", function() {
                    location.reload();
                });
            });
 // Hàm cập nhật giá của sản phẩm dựa trên thay đổi số lượng
            function updateCartItemPrice(productId, newQuantity) {
                // Thực hiện tính toán giá tiền mới dựa trên số lượng mới và cập nhật lại trên trang
                var productPrice = parseFloat($(".item-price[data-pid='" + productId + "']").text().replace("$", ""));
                var newPrice = productPrice * newQuantity;
                $(".item-price[data-pid='" + productId + "']").text("$" + newPrice.toFixed(2));
            }
// Hàm cập nhật tổng giá trị dựa trên thay đổi của sản phẩm
            function updateTotalAmount() {
                var totalAmount = 0;
                $(".item-quantity").each(function() {
                    totalAmount += parseFloat($(this).val()) * parseFloat($(this).siblings(".item-price").text().replace("$", ""));
                });
                $(".cart-total p").text("Total: $" + totalAmount);
            }
        });
    </script>
</body>

</html>