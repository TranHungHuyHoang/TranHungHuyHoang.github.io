<?php
require("libs/DatabaseClass.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="format-detection" content="telephone=no">
    <title>Electro</title>

    <link rel="stylesheet" href="css/util.css"/>
    <link rel="stylesheet" href="css/items.css"/>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <script src="https://kit.fontawesome.com/57448d1974.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <!-- <script src="js/cart.js"></script> -->


</head>
<!--Body section-->
<body>
<!--Navbar-->
<?php
if (!isset($_SESSION["user_name"]))# Kiểm tra xem người dùng đã đăng nhập chưa.
    include "libs/nav_guest.php"; // Nếu chưa đăng nhập, sử dụng file để hiển thị navbar cho khách.           
?>

<!--Content text-->
#Một phần header với class jumbotron và chứa tiêu đề "Product List" và mô tả về các sản phẩm laptop.
<div class="jumbotron jumbotron-fluid text-center"> 
  <div class="container-fluid" style="background-color: #4285f4; padding: 3rem;">
    <h2 style= "font-family: Gilroy Bold;
                font-size: 40px;
                text-decoration: none;
                color: white;">
                Product List
    </h2>
    <p style= "font-family: Gilroy Medium;
                text-decoration: none;
                color: white;">
                Latest models of laptop from different brands
    </p>
  </div>
</div>

<!--Product list-->
# Sử dụng file product_list.php để hiển thị danh sách sản phẩm laptop.
<div>
    <?php
    include "libs/product_list.php";
    ?>
</div>

<!--footer-->
# Sử dụng file footer.php để hiển thị phần chân trang.
<?php
include "libs/footer.php"; // Using footer file here
?>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"
        integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"
        integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1"
        crossorigin="anonymous"></script>
</body>
</html> 

