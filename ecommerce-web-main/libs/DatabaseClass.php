<?php

class DatabaseClass
{
    private $conn = null;

    // this function is called everytime this class is instantiated
    public function __construct($servername = "localhost", $db_name = "electro", $user_name = "root", $password = ""){
        $this->conn = mysqli_connect($servername, $user_name, $password, $db_name);
        mysqli_set_charset($this->conn, 'UTF8');

        if (!$this->conn) {
            echo "Connection Failed.";
        }
    }
#Phương thức này được gọi mỗi khi một đối tượng của class được khởi tạo.
# Nó thiết lập kết nối đến cơ sở dữ liệu với các tham số truyền vào.
    // Get all products
    public function getAllProducts() {
        $sql = "CALL getLaptopProducts";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }
#Phương thức này lấy tất cả sản phẩm từ cơ sở dữ liệu bằng cách 
#gọi một stored procedure getLaptopProducts.
    // Get filtered products
    public function getFilteredProducts($product_name, $brand, $os, $cpu, $ram, $storage, $min_price, $max_price) {
        $sql = "CALL filterProducts(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssii", $product_name, $brand, $os, $cpu, $ram, $storage, $min_price, $max_price);
        $stmt->execute();
        return $stmt->get_result();
    }
#Phương thức này lấy các sản phẩm được lọc theo các tiêu chí nhất định,
# sử dụng stored procedure filterProducts.
    public function getProductsByName($product_name) {
        $sql = "CALL getProductsByName(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $product_name);
        $stmt->execute();
        return $stmt->get_result();
    }
#Phương thức này lấy các sản phẩm theo tên sản phẩm, sử dụng stored procedure getProductsByName.
    public function getCartItems($session_id) {
        $sql = "CALL getCartItems(?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $session_id);
        $stmt->execute();
        return $stmt->get_result(); // fetch data from database
    }
#Phương thức này lấy các mục trong giỏ hàng của người dùng dựa trên session_id, sử dụng stored procedure getCartItems.
    public function calCartQuantity($user_id) {
        $sql = "CALL calCartQuantity(?)";
        $stmt = $this->conn->prepare($sql); 
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function conn_next() {
        $this->conn->next_result();
    }
}
#Phương thức này tính toán số lượng sản phẩm trong giỏ hàng của người dùng dựa trên user_id, sử dụng stored procedure calCartQuantity.
$db = new DatabaseClass();
#Phương thức này dùng để chuyển đến kết quả tiếp theo của truy vấn nếu có nhiều hơn một kết quả trả về từ stored procedure.
// If you have password, use the line below
// $db = new DatabaseClass(password: "");