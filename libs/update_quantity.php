<?php
include "db_conn.php";
session_start();

if (isset($_POST['action']) && isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $action = $_POST['action'];
    $productId = $_POST['product_id'];
    $newQuantity = $_POST['quantity'];
    $session_id = $_SESSION['shop_session'];

    if ($action === "update") {
        $sql = "UPDATE cart_item SET quantity = ? WHERE session_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis", $newQuantity, $session_id, $productId);
        $stmt->execute();
        $stmt->close();

        // Cập nhật giá tiền trong session nếu cần thiết
        $_SESSION['total_amount'] = calculateTotalAmount();

        echo "success";
    } else if ($action == 'delete') {
        $sql = "DELETE FROM cart_item WHERE session_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $session_id, $productId);
        $stmt->execute();
        $stmt->close();

        // Cập nhật giá tiền trong session nếu cần thiết
        $_SESSION['total_amount'] = calculateTotalAmount();

        echo "success";
    } else {
        echo "Invalid action";
    }
} else {
    echo "Invalid request";
}

function calculateTotalAmount()
{
    global $conn, $session_id;
    // Thực hiện tính toán lại tổng giá tiền dựa trên thông tin trong cơ sở dữ liệu
    $sql = "CALL calCartQuantity(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $_SESSION['id']);
    $stmt->execute();
    $sum_records = $stmt->get_result();
    $sum_cart_items = mysqli_fetch_assoc($sum_records);
    return isset($sum_cart_items['total']) ? $sum_cart_items['total'] : 0;
}
